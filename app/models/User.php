<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface
{

    protected $fillable = array('username', 'email', 'password', 'password_confirmation');

    protected static $rules = array(
        'username' => 'required|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed',
        'password_confirmation' => 'required'
    );

    /**
     * Validate the data against the suppled rules and perform any model
     * specific changes to the data.
     *
     * @return bool Perform save?
     */
    public function validate()
    {
        if (!parent::validate()) {
            return false; // Validation failed.
        }

        // We only needed the confirmation field for validation, discard it before save.
        unset($this->attributes['password_confirmation']);

        // Hash the password.
        $this->attributes['password'] = Hash::make($this->attributes['password']);

        return true; // Save the user.
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'status');

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function stories()
    {
        return $this->hasMany('Story');
    }

    public function started()
    {
        return $this->hasMany('Story');
    }

    public function collaborations()
    {
        return $this->belongsToMany('Story')->whereActive(1)->whereCreator(0);
    }

    public function invitations()
    {
        return $this->belongsToMany('Story')->whereActive(0);
    }

    public function linkTo()
    {
        return HTML::link('profile/' . $this->attributes['id'], $this->attributes['username']);
    }

    // Helper functions

    public function inviteAccepted()
    {
        return $this->pivot->active == 1;
    }

    public function isMe()
    {
        return Auth::check() && Auth::user()->id == $this->id;
    }

    // TODO : Testing methods below should be removed

    public function become()
    {
        return HTML::linkRoute('sessions.becomeuser', $this->username, [$this->id]);
    }

}
