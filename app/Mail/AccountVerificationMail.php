<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function build()
    {
        $verifyUrl = url('/verify-account/' . $this->token);

        return $this->subject('Xác nhận tài khoản - ElectroShop')
            ->view('emails.account_verification')
            ->with([
                'verifyUrl' => $verifyUrl,
                'email' => $this->email,
                'token' => $this->token,
            ]);
    }
}
