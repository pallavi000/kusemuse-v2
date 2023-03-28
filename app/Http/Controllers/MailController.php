<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\User;

use Illuminate\Http\Request;

class MailController extends Controller
{
    // test mail
    public function sendEmail(){
        $details=[
            'title'=>'Order Successfully!!',
            'body'=>'Your Order is conformed.
            And we are just as excited as you are!!'
        ];
        //$user= User::all();
        Mail::to("abc@gmail.com")->send(new TestMail($details));
        return 'email sent';
    }

    public function testemail(){
        return view('emails.TestMail');
    }

}
