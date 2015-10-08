<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{

    /**
     * Display the profile for the currently logged in user.
     *
     * @return \Illuminate\View\View
     */
    public function profile() {
        return view('account.profile', [
            'nav' => ''
        ]);
    }

}
