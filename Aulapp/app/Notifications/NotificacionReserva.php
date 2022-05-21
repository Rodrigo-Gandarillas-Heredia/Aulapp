<?php

namespace App\Notifications;

use App\Models\reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionReserva extends Notification
{
 use Queueable;
 public $reserva;
 /**
  * Create a new notification instance.
  *
  * @return void
  */
 public function __construct(reserva $reserva)
 {
  $this->reserva = $reserva;
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
  return (new MailMessage)
   ->line($this->reserva->motivo)
   ->line('Esta es una notificación de prueba')
   ->line('la cantidad de estudiantes es: ' . $this->reserva->cantE)
   ->line($this->reserva->user_rol->usuario->Email);
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