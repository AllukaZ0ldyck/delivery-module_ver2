<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $qrPath;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->qrPath = asset('storage/' . $user->qr_code); // ✅ Full QR image URL
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your AquaTek Water Station Account Has Been Approved!')
            ->markdown('emails.account_approved');
    }
}
