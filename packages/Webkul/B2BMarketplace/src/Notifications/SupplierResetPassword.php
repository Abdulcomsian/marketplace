<?php

namespace Webkul\B2BMarketplace\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class SupplierResetPassword extends ResetPassword
{

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->from(core()->getSenderEmailDetails()['email'])
            ->subject(__('b2b_marketplace::app.mail.forget-password.subject') )
            ->view('b2b_marketplace::emails.supplier.forget-password', [
                'user_name' => $notifiable->name,
                'token' => $this->token
            ]);
    }
}
