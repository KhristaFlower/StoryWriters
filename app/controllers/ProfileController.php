<?php

class ProfileController extends BaseController {

    public function __construct()
    {
        $variables = array(
            'nav' => 'profile'
        );
        View::share($variables);

        $this->beforeFilter('auth', array('only' => ['index']));
    }

	/**
	 * Display the current users profile.
	 *
	 * @return Response
	 */
	public function index()
	{
        $user = User::with('started', 'collaborations', 'invitations')->find(Auth::user()->id);

        return View::make('profiles.index', compact('user'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $user = User::with('started', 'collaborations')->find($id);
        //dd($user->started);
        return View::make('profiles.show', compact('user'));
	}

}
