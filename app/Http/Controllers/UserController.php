<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;


/**
 * Class UserController
 * User controller for login, check session and logout
 *
 * @package App\Http\Controllers
 * @author Tengyu Wang
 */
class UserController extends Controller
{
    /**
     * Load Login page viewer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Check if user's login details are fine or not, password length minimum 6 and should be alphanumeric
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:6'
        ]);

        $userCredentials = ['email' => $request->get('email'), 'password' => $request->get('password')];

        if (Auth::attempt($userCredentials)) {
            return redirect('movies');
        } else {
            return back()->with('error', 'Incorrect Login details!');
        }
    }

    /**
     * Check if session still alive
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSession()
    {
        if (isset(Auth::user()->email)) {
            $login = true;
        } else {
            $login = false;
        }

        return response()->json(['login' => $login]);
    }

    /**
     * Logout and redirect to login page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
