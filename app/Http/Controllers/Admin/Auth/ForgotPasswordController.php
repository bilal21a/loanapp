<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    // public function __construct()
    // {
    //     $this->middleware('guest:admin');
    // }

    public function showLinkRequestForm() {
        return view('website.admin.auth.email');
    }

    protected function broker() {
        return Password::broker('admins');
    }
}
