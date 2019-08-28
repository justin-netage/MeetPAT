<?php

namespace MeetPAT\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactSend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Message Instance
    *
    * @var $message_details
    */

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $message_details;

    public function __construct($message_details)
    {
        //
        $this->message_details = $message_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@meetpat.co.za', $this->message_details['name'])->markdown('emails.contact.send')
                    ->subject('MeetPAT - Online Query')
                    ->with(['message' => $this->message_details['message'],
                            'email' => $this->message_details['email'],
                            'name' => $this->message_details['name']]);
    }
}
