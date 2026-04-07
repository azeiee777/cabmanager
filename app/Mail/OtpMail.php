<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $purpose;

    public function __construct($code, $purpose = 'signup')
    {
        $this->code = $code;
        $this->purpose = $purpose;
    }

    public function envelope(): Envelope
    {
        $subject = $this->purpose === 'password-reset'
            ? 'Your CabManager Password Reset Code: ' . $this->code
            : 'Your CabManager Verification Code: ' . $this->code;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $headline = $this->purpose === 'password-reset' ? 'Reset Your Password' : 'CabManager Elite';
        $bodyText = $this->purpose === 'password-reset'
            ? 'Use the code below to reset your CabManager password securely.'
            : 'Use the code below to verify your account and join the fleet.';

        return new Content(
            view: 'emails.otp',
            with: [
                'headline' => $headline,
                'bodyText' => $bodyText,
            ],
        );
    }
}
