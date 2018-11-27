<?php

namespace MeetPAT\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Message Instance
    *
    * @var $user_details
    */

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user_details;

    public function __construct($user_details)
    {
        //
        $this->user_details = $user_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@meetpat.co.za', $this->user_details['email'])->markdown('emails.new_user')
                    ->subject('MeetPAT - New Registration')
                    ->with(['message' => $this->user_details['message'],
                            'email' => $this->user_details['email'],
                            'name' => $this->user_details['name'],
                            'password' => $this->user_details['password']]);
    }
}
