<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $otp;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otp)
    {
        //parent::__construct($otp);
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return ( new MailMessage )
            ->subject( 'Reset Password Notification' )
            ->line( "You are receiving this email because we received a password reset request for your account." )
            ->line( "Use ".$this->otp." to reset your password")
            ->line( "This password reset code will expire in ".config('auth.passwords.users.expire')." minutes" )
            ->line( "If you did not request a password reset, no further action is required." ); 
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
