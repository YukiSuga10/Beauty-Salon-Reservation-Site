<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReserveConfirm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $title;
    protected $text; 
     
     
    public function __construct($name, $text, $reserves)
    {
        $this->title = sprintf('%s様',$name);
        $this->text = $text;
        $this->reserves = $reserves;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('confirm_mail')
                    ->subject($this->title)
                    ->with([
                        'text' => $this->text,
                        'reserves' => $this->reserves,
                      ]);
    }
}
