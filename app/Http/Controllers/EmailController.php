<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    //

    public function sendReport(Request $request){
    //Logic will go here

    // dd('hello');

    // $title = $request->input('title');
    // $content = $request->input('content');
    //
    // Mail::send('emails.send', ['title' => $title, 'content' => $content], function ($message)
    // {
    //
    //     $message->from('admin@synterra.co.th', 'Syntera Co., Ltd.');
    //     $message->to('asavadorndeja@hotmail.com');
    //
    // });

    // return response()->json(['message' => 'Request completed']);
    $request->session()->flash('alert-success', 'User was successful added!');
    return redirect()->back();
    
    }

}
