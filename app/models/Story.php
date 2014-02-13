<?php

class Story extends BaseModel
{
    protected $fillable = array(
        'title',
        'theme',
        'user_id',
        'status',
        'current_writer',
        'invite_mode',
        'max_writers',
        'min_words_per_segment',
        'max_words_per_segment',
        'write_mode'
    );

    protected static $rules = array(
        'title' => "required|max:255|regex:/^[A-Za-z0-9 ,'_-]+$/",
        'theme' => 'required|max:64|alpha_num',
        'user_id' => 'required|integer',
        'status' => 'required|integer',
        'current_writer' => 'required|integer',
        'invite_mode' => 'required|in:1,2,3,4',
        'max_writers' => 'required|integer|digits_between:1,10',
        'min_words_per_segment' => 'required|integer|min:3|max:250',
        'max_words_per_segment' => 'required|integer|min:3|max:250',
        'write_mode' => 'required|in:1,2,3'
    );

    // Invite Constants
    const INVITE_CREATOR_ONLY = 1;
    const INVITE_PRIVATE_ONLY = 2;
    const INVITE_PRIVATE_LINK = 3;
    const INVITE_PUBLIC_LINK = 4;

    // Write Constants
    const WRITE_ORDERED = 1;
    const WRITE_RANDOM = 2;
    const WRITE_ANY = 3;

    // Status Constants
    const STATUS_INIVTE = 1;
    const STATUS_WRITING = 2;
    const STATUS_FINISHED = 3;


    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        // Validation passed; flip the min/max if the user decided to get funny with us...
        $min = $this->attributes['min_words_per_segment'];
        $max = $this->attributes['max_words_per_segment'];

        if ($min > $max) {
            $this->attributes['min_words_per_segment'] = $max;
            $this->attributes['max_words_per_segment'] = $min;
        }

        // Return true and allow the model to save.
        return true;
    }

    //-------------------------------------------------------------------------
    // Relationships
    //-------------------------------------------------------------------------

    public function creator()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

    public function writers()
    {
        return $this->belongsToMany('User', 'story_user', 'story_id', 'user_id')->withPivot('active');
    }

    public function activeWriters()
    {
        return $this->belongsToMany('User', 'story_user', 'story_id', 'user_id')->withPivot('active')->whereActive(1);
    }

    public function invitedWriters()
    {
        return $this->belongsToMany('User', 'story_user', 'story_id', 'user_id')->withPivot('active')->whereActive(0);
    }

    public function segments()
    {
        return $this->hasMany('Segment');
    }

    //-------------------------------------------------------------------------
    // Helper Methods
    //-------------------------------------------------------------------------

    /**
     * Get a human-reable representation of an enum settings value.
     *
     * @param $key string The attribute whos value is an enum value.
     * @return string
     */
    public function valueToString($key)
    {
        $invite_mode = array(
            1 => 'Creator Only',
            2 => 'Creator and Invited',
            3 => 'Anyone via Private Link',
            4 => 'Anyone via Public Listing'
        );

        $write_mode = array(
            1 => 'Ordered Writing',
            2 => 'Random Order',
            3 => 'Free For All'
        );

        if (!isset(${$key}))
            return "$key not set";

        // Get the description above for the value associated with the provided key.
        return ${$key}[$this->attributes[$key]];
    }

    /**
     * Get a collection of human-reable values for the stories settings.
     *
     * @return array
     */
    public function getSettings()
    {
        $settings = array(
            'Write Mode' => $this->valueToString('write_mode'),
            'Invite Mode' => $this->valueToString('invite_mode'),
            'Max Writers' => count($this->writers) . "/" . $this->max_writers,
            'Min Words' => $this->min_words_per_segment,
            'Max Words' => $this->max_words_per_segment
        );
        if ($this->isInviteMode() && $this->invite_mode == self::INVITE_PRIVATE_LINK) {
            $settings['Invite Link'] = $this->inviteLink('Share Me');
        }
        return $settings;
    }

    public function getMinWords()
    {
        return $this->min_words_per_segment;
    }

    public function getMaxWords()
    {
        return $this->max_words_per_segment;
    }

    /**
     * Get a formatted link to the page for this story.
     *
     * @return string
     */
    public function linkTo()
    {
        return HTML::link('/stories/' . $this->attributes['id'], $this->attributes['title']);
    }

    /**
     * Check that the current user is the creator of this story.
     *
     * @return bool
     */
    public function isCreator()
    {
        return Auth::check() && Auth::user()->id == $this->creator->id;
    }

    public function isInviteMode()
    {
        return $this->status == self::STATUS_INIVTE;
    }

    public function isWriteMode()
    {
        return $this->status == self::STATUS_WRITING;
    }

    public function isFinished()
    {
        return $this->status == self::STATUS_FINISHED;
    }

    /**
     * Check whether the current authenticated user has permission
     * invite others.
     *
     * @return bool
     */
    public function invitedCanInvite()
    {
        // Users have to be logged in AND the story has to be set to be
        // set to private invitations only.
        if (!Auth::check() || $this->invite_mode != self::INVITE_PRIVATE_ONLY)
            return false;

        // Prevent sending out invitations if we've reached the set maximum.
        if (count($this->writers) >= $this->max_writers)
            return false;

        $user = Auth::user();

        // Check if we are one of the writers.
        foreach ($this->writers as $writer) {
            if ($writer->id == $user->id) {
                // Check to see if we have accepted our invitation.
                if ($writer->pivot->active == 1) {
                    return true;
                } else {
                    return false; // Stop looking.
                }
            }
        }

        return false;
    }

    public function isPublicInvite()
    {
        return Auth::check() && $this->invite_mode == self::INVITE_PUBLIC_LINK;
    }

    public function isLinked($id = null)
    {
        // We didn't supply an ID, check the current user.
        if ($id == null) {
            // Is the current user logged in?
            if (!Auth::check())
                return false;

            $id = Auth::user()->id;
        }

        foreach ($this->writers as $writer) {
            if ($writer->id == $id)
                return true;
        }

        return false;
    }

    public function myTurn()
    {
        return Auth::check() && Auth::user()->id == $this->current_writer;
    }

    public function inviteKey()
    {
        return md5($this->id . $this->created_at);
    }

    public function inviteLink($title = 'Invite Link')
    {
        return HTML::linkRoute('stories.join', $title, [$this->id, $this->inviteKey()]);
    }

    public function isFull()
    {
        return $this->writers->count() >= $this->max_writers;
    }

}
