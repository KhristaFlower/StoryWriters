<?php

class WriterController extends BaseController {

    public function showAll()
    {
        $writers = User::paginate(30);
        $nav = "Writers";

        return View::make('writers.index', compact('writers', 'nav'));
    }

    /**
     * Add a user as a writer to the story.
     *
     * @param $id Integer The story to add the writer to.
     * @return Response
     */
    public function addWriter($id)
    {
        $errors = new \Illuminate\Support\MessageBag();

        // Check to ensure the user we wish to add exists.
        $user = User::whereUsername(Input::get('writer'))->first();
        if (is_null($user)) {
            $errors->add('writer', "No writer with that username.");
            return Redirect::back()->withErrors($errors);
        }

        $story = Story::with('writers')->find($id);

        // If we are not the creator, ensure we are below story max for writers.
        if (count($story->writers) >= $story->max_writers && !$story->isCreator())
            return Redirect::back()->with('flash_message', "Maximum writers added to the story.");

        // Test if the user we wish to add is a writer of this story already.
        $writer = $story->writers()->whereUserId($user->id)->first();
        if (!is_null($writer)) {
            $errors->add('writer', "That writer is already a part of this story.");
            return Redirect::back()->withErrors($errors);
        }

        // Add the writer to the story.
        $story->writers()->attach($user->id);

        return Redirect::back()
            ->with('flash_message', "{$user->username} has been added to the story.")
            ->with('flash_type', 'success');
    }

    /**
     * Accepts an invite to the story for the authenticated user.
     *
     * @param $id Integer The id of the story to accept for.
     * @return Response
     */
    public function acceptInvite($id)
    {
        $user = Auth::user();

        // Grab the current user's relationship entry for this story.
        $writer = Story::find($id)->writers()->whereUserId($user->id)->whereActive(0)->first();

        if (is_null($writer))
            return Redirect::back();

        // Modify and save the pivot table.
        $writer->pivot->active = 1;
        $writer->pivot->save();

        return Redirect::back()
            ->with('flash_message', "You have joined the writers of this story.")
            ->with('flash_type', 'success');
    }

    /**
     * Declines an invite to the story for the authenticated user.
     *
     * @param $id Integer The id of the story to decline for.
     * @return Response
     */
    public function declineInvite($id)
    {
        $user = Auth::user();

        // Remove the user from this story.
        Story::find($id)->writers()->whereUserId($user->id)->whereActive(0)->detach($user->id);

        return Redirect::route('stories.index')->with('flash_message', "You have not joined the writers of this story.");
    }

    /**
     * Cancel a pending invitation to a story.
     *
     * @param $story_id Integer The id of the story to cancel from.
     * @param $user_id Integer The id of the user to cancel.
     * @return Response
     */
    public function cancelInvite($story_id, $user_id)
    {
        $story = Story::find($story_id);

        // Validate that the action is being performed by the creator.
        if (!$story->isCreator())
            return Redirect::back();

        // Remove the invitation.
        $story->writers()->whereUserId($user_id)->whereActive(0)->detach($user_id);

        return Redirect::back()->with('flash_message', "Invitation cancelled.");
    }

    /**
     * Leave the specified story if it has not started yet.
     *
     * @param $id Integer The id of the story to leave.
     * @return Response
     */
    public function leaveStory($id)
    {
        $story = Story::find($id);

        // Can't leave if we are the creator.
        if ($story->isCreator())
            return Redirect::back()->with('flash_message', 'You cannot leave as the creator.');

        // Can't leave if the story has stated.
        if (!$story->isInviteMode())
            return Redirect::back()->with('flash_message', 'You cannot leave once the story has started.');

        $user = Auth::user();

        // Detach the user if they are a part of this story.
        $story->writers()->whereUserId($user->id)->whereActive(1)->detach($user->id);

        return Redirect::back()->with('flash_message', 'You have left the story.');
    }

}
