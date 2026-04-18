<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\ExamResult;


class ExamResultMail extends Mailable
{
    public $results;
    public $user;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function build()
    {
        return $this->subject('Your Exam Results')
                    ->view('emails.exam_result');
    }   
}
