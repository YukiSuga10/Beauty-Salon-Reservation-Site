<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EditConfirm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $title;
    protected $text; 
     
     
    public function __construct($name, $text, $edit)
    {
        $this->title = sprintf('%s様',$name);
        $this->text = $text;
        $this->edit = $edit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.edit')
                    ->subject($this->title)
                    ->with([
                        'text' => $this->text,
                        'edit' => $this->edit,
                      ]);
    }
}
