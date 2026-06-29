<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $booking = $this->booking;
        $tour    = $booking->tour?->getName('ru') ?? 'Индивидуальный';

        return (new MailMessage())
            ->subject('Новая заявка #' . $booking->id . ' — ' . $booking->name)
            ->view('emails.new-booking', compact('booking', 'tour'));
    }
}
