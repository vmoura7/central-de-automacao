<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class SiteAvailabilityNotification extends Notification
{
    use Queueable;

    protected $siteUrls;
    protected $statusCode;

    /**
     * Create a new notification instance.
     *
     * @param string $siteUrls
     * @param int $statusCode
     * @return void
     */
    public function __construct($siteUrls, $statusCode)
    {
        $this->siteUrls = $siteUrls;
        $this->statusCode = $statusCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */

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
