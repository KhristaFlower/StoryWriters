<?php

class Segment extends Eloquent
{

    protected $fillable = ['user_id', 'story_id', 'content'];

    public function user()
    {
        return $this->belongsTo('User');
    }

}
