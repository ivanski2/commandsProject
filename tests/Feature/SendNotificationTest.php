<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArchiveNotification;

class SendNotificationTest extends TestCase
{
    /** @test */
    public function it_sends_email_notification_on_success()
    {
        Mail::fake();

        $this->artisan('app:archive --password=example')
            ->assertExitCode(0);

        Mail::assertSent(ArchiveNotification::class, function ($mail) {

            return $mail->hasTo('ivanski34@gmail.com');
        });

        Mail::assertSent(ArchiveNotification::class, 1);
    }
}
