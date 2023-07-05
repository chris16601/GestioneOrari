<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailContact;

class SendEmailController extends Controller
{
    public function sendEmail(){
        $data = [
            'subject' => 'Send Email',
            'body' => 'Hello, My Name is Gustavo Fring',
        ];
        try {
            Mail::to('barillodue@gmail.com')->send(new MailContact($data));
            return response()->json(['Inviata, Controlla la tua email']);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(["Ci scusiamo errore nell'invio dell'email"]);
        }
    }
}
