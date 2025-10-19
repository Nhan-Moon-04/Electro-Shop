<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $resetUrl = url('/reset-password/' . $this->token);

        return $this->subject('Yêu cầu đặt lại mật khẩu - ElectroShop')
            ->view('emails.password_reset_token')
            ->with([
                'resetUrl' => $resetUrl,
                'email' => $this->email,
                'token' => $this->token,
            ]);
    }
}
