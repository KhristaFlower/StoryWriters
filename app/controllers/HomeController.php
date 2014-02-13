<?php

class HomeController extends BaseController {

	public function homepage()
	{
        $variables = array(
            'nav' => 'Home'
        );
        View::share($variables);

		return View::make('home');
	}

    public function about()
    {
        $nav = "About";
        return View::make('about', compact('nav'));
    }

}