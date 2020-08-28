<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{

    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject('Bevestig uw accountregistratie')
            ->greeting('Beste gebruiker,')
            ->line(new HtmlString('Er is een account gecre&euml;erd op dit e-mailadres voor de applicatie HU Werkplekleren (&quot;de Stage-App&quot;).'))
            ->line('Klik op de onderstaande knop om het account te activeren en gebruik te maken van HU Werkplekleren.')
            ->action(
                ('Registratie bevestigen'),
                $this->verificationUrl($notifiable)
            )
            ->line(new HtmlString('Als je dit niet zelf gedaan hebt, geef dit dan alsjeblieft zo snel mogelijk door aan <a href="mailto:debug@werkplekleren.hu.nl">debug@werkplekleren.hu.nl</a> zodat we dit account kunnen deactiveren.'))
            ->line(new HtmlString('Gebruik van deze applicatie valt onder de algemene voorwaarden van de HU. Meer informatie is te vinden op <a href="https://www.hu.nl/privacy">https://www.hu.nl/privacy</a>.'))
            ->line(new HtmlString('Meer informatie over de gegevensverwerking door deze applicatie is te vinden in de toestemmingsverklaring op <a href="https://werkplekleren.hu.nl/assets/pdf/privacyverklaring.pdf">https://werkplekleren.hu.nl/assets/pdf/privacyverklaring.pdf</a>'));
                
    }
}
