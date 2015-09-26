<?php

class StoryController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter('auth', ['only' => ['create', 'store']]);

        $variables = array(
            'nav' => 'Stories'
        );
        View::share($variables);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = Request::get('q');

        if ($query) {
            $stories = Story::where('title', 'LIKE', "%$query%")->whereStatus(2)->paginate(15);
        } else {
            $stories = Story::with('creator')->whereStatus(2)->paginate(15);
        }


        return View::make('stories.index')->with(compact('stories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('stories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();

        $creator = Auth::user();
        $input['user_id'] = $creator->id;
        $input['status'] = 1;
        $input['current_writer'] = $creator->id;

        $story = Story::create($input);

        if (!$story->save()) {
            return Redirect::back()->withInput()->withErrors($story->getErrors());
        }

        // Successful save, add the creator as a writer.
        $story->writers()->attach($creator->id, array('active' => 1, 'creator' => 1));

        return Redirect::to('/stories/' . $story->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $story = Story::with('creator', 'writers', 'segments', 'segments.user')->find($id);

        // Hide from prying if in private invitation mode.
        if ($story->status == 1 && $story->invite_mode != Story::INVITE_PUBLIC_LINK) {
            // Only allow to be viewed from active or invited writers.
            if (!$story->isLinked())
                return Redirect::route('stories.index');
        }

        return View::make('stories.show')->with(compact('story'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        return View::make('stories.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    //-------------------------------------------------------------------------
    // Display Functions
    //-------------------------------------------------------------------------

    public function storiesFinished()
    {
        $stories = Story::whereStatus(3)->paginate(15);

        return View::make('stories.index', compact('stories'));
    }

    public function storiesOngoing()
    {
        $stories = Story::whereStatus(2)->paginate(15);

        return View::make('stories.index', compact('stories'));
    }

    public function storiesStarting()
    {
        $stories = Story::whereStatus(1)->whereInviteMode(4)->paginate(15);

        return View::make('stories.index', compact('stories'));
    }

    //-------------------------------------------------------------------------
    // CONTROL METHODS
    //-------------------------------------------------------------------------

    /**
     * Start the story and allow the writing to begin.
     *
     * @param $id Integer The id of the story to begin.
     * @return Response
     */
    public function beginStory($id)
    {
        // Grab the story if it has not yet been started.
        $story = Story::whereId($id)->whereStatus(1)->first();

        if (is_null($story))
            return Redirect::back();

        // We can only start if the action was triggered by the creator.
        if (Auth::user()->id != $story->user_id)
            return Redirect::back();

        $story->status = 2;
        $story->save();

        // TODO : Add an option to randomly select the first writer.
        //$this->selectNextWriter();

        return Redirect::back()->with('flash_message', 'Let the writing begin!');
    }

    /**
     * Validate and add a segment to the database.
     * todo : extract functionality to seperate validation class.
     *
     * @param $id Integer The id of the story to compose the segment to.
     * @return Response
     */
    public function composeSegment($id)
    {
        $errors = new \Illuminate\Support\MessageBag();

        // Get the story we are posting to.
        $story = Story::find($id);

        if (is_null($story))
            return Redirect::route('stories.index');

        // Ensure we can post.
        if (!$story->myTurn()) {
            return Redirect::back()->with('flash_message', 'It is not your turn to post.');
        }

        // Validate the segment.

        $segment = Input::get('segment');

        $segment = strip_tags($segment); // Rmeove HTML tags.
        $segment = preg_replace('/[ ]+/', " ", $segment); // Remove multiple spaces.
        $segment = trim($segment, " "); // Trim extra whitespace at the start and end.
        $segment = preg_replace("/\n\s*\n\s*\n/", "\n\n", $segment); // Remove excess newlines.

        // Count the number of words.
        // Replace all newlines with spaces for easier calculation.
        $words = preg_replace("/[\r\n|\r|\n]+/", " ", $segment);
        $words = explode(" ", $words);
        $wordcount = count($words);

        // Check to make sure we don't have stupidly long words. This is to stop someone
        // posting 100 words all without spaces. It is unrealistic.
        foreach ($words as $word) {
            if (strlen($word) > 20) {
                $errors->add('segment', substr($word, 0, 20) . "... is unreasonably large.");
                return Redirect::back()->withInput()->withErrors($errors);
            }
        }

        // Ensure we meet the minimum word quota for the story.
        if ($wordcount < $story->getMinWords()) {
            $errors->add('segment', "Too few words.");
            return Redirect::back()->withInput()->withErrors($errors);
        }

        // Ensure we don't exceed the maximum word quota for the story.
        if ($wordcount > $story->getMaxWords()) {
            $errors->add('segment', "Too many words.");
            return Redirect::back()->withInput()->withErrors($errors);
        }

        // If we had to make changes to the segment removing illegal content,
        // verify with the submitter that everything is still okay.
        // The next submission should not require editing and should pass ok.
        if (Input::get('segment') != $segment) {
            $errors->add('segment', "Some small changes were made to the formatting of your segment. Verify that everything is correct and submit again.");

            // Replace the old input with the new input. The user will verify this is ok.
            $input = Input::all();
            $input['segment'] = $segment;
            Input::replace($input);

            return Redirect::back()->withInput()->withErrors($errors);
        }

        $input = Input::all();
        $input['user_id'] = Auth::user()->id;
        $input['story_id'] = $id;
        $input['content'] = $input['segment'];
        unset($input['segment']);

        $segment = Segment::create($input);

        if (!$segment->save()) {
            return Redirect::back()->withInput()->with('flash_message', 'Unable to save, something went wrong.');
        }

        $this->selectNextWriter($id);

        return Redirect::back()->with('flash_message', "Segment saved!");
    }

    public function joinStory($id, $key)
    {
        if (!Auth::check())
            return Redirect::intended('stories.index');

        $story = Story::with('creator')->find($id);

        // Check the key.
        if ($key != $story->inviteKey())
            return Redirect::route('stories.index')
                ->with('flash_message', "Failed to join story.")
                ->with('flash_type', 'danger');

        // Add the user to the story.
        $story->writers()->attach(Auth::user()->id);

        return Redirect::route('stories.show', $story->id)
            ->with('flash_message', "You have recieved an invitation, please review
            the story settings either accpet or decline the invite.")
            ->with('flash_type', 'warning');
    }

    //-------------------------------------------------------------------------
    // Private Functions
    //-------------------------------------------------------------------------

    private function selectNextWriter($id)
    {
        $story = Story::with('writers')->find($id);
        $writers = $story->writers;

        $nextWriter = null;

        if ($story->write_mode == Story::WRITE_ORDERED) {
            // Select the next writer.
            //------------------------
            for ($i = 0; $i < count($writers); $i++) {
                if ($writers[$i]->id == $story->current_writer) {
                    // This writer is the current, select the next one.
                    // If the next one doesn't exist, grab the first.
                    if (isset($writers[$i+1])) {
                        $nextWriter = $writers[$i+1];
                    } else {
                        $nextWriter = $writers[0];
                    }
                }
            }
        } elseif ($story->write_mode == Story::WRITE_RANDOM) {
            // Select a random writer.
            //------------------------
            $randomWriters = array();

            // Collect up a list of everyone who can make a turn next.
            for ($i = 0; $i < count($writers); $i++) {
                if ($writers[$i]->id != $story->current_writer) {
                    $randomWriters[] = $writers[$i];
                }
            }

            // Select a random writer.
            $writerIndex = rand(0, count($randomWriters)-1);
            $nextWriter = $randomWriters[$writerIndex];
        }

        // Assign the new writer to the story.
        $story->current_writer = $nextWriter->id;
        $story->save();
    }

}
