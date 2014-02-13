<?php

class AccountController extends BaseController
{

    public function __construct()
    {
        $variables = array('nav' => 'Account');
        View::share($variables);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        View::share('nav', 'Register');
        return View::make('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // Mass assign the form input to the user.
        $user = new User(Input::all());

        if (!$user->save()) {
            // If we failed to save the user due to errors, redirect back.
            return Redirect::back()->withInput()->withErrors($user->getErrors());
        }

        return Redirect::to('/login')->with('flash_message', 'Account created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return View::make('accounts.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        return View::make('accounts.edit');
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

}
