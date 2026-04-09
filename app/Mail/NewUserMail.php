<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserMail extends Mailable
{
    use SerializesModels;

    public $user;

    public $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->markdown('emails.user.new_user', ['user' => $this->user, 'password' => $this->password])->subject('Nueva cuenta de usuario');
    }
}
