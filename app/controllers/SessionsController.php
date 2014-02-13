<?php

class SessionsController extends BaseController {

    public function __construct()
    {
        $variables = array(
            'nav' => 'Login'
        );
        View::share($variables);
    }

	/**
	 * Show the login form.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('sessions.create');
	}

	public function store()
	{
        $input = Input::all();

        $attempt = Auth::attempt([
            'username' => $input['username'],
            'password' => $input['password']
        ]);

        if ($attempt)
            return Redirect::intended('profile')
                ->with('flash_message', 'You have logged in.')
                ->with('flash_type', 'success');

		return Redirect::back()->withInput()
            ->with('flash_message', 'Invalid credentials.')
            ->with('flash_type', 'danger');
	}

	public function destroy()
	{
		Auth::logout();

        return Redirect::to('/')
            ->with('flash_message', 'You have logged out.')
            ->with('flash_type', 'success');
	}

    // TODO : Remove test methods below

    public function becomeUser($id)
    {
        $user = User::find($id);

        if (is_null($user))
            return Redirect::to('/');

        Auth::login($user);

        return Redirect::back()
            ->with('flash_message', "You have become {$user->username}!")
            ->with('flash_type', "success");
    }

}
