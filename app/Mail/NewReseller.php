<?php

namespace MeetPAT\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReseller extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
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
        return $this->from('info@meetpat.co.za', $this->user_details['email'])->markdown('emails.new_reseller')
                    ->subject('MeetPAT - New Registration')
                    ->with(['message' => $this->user_details['message'],
                            'email' => $this->user_details['email'],
                            'name' => $this->user_details['name'],
                            'password' => $this->user_details['password']]);
    }
}
