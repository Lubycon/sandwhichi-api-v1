<?php

namespace App\Jobs\Mails;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use Password;
use App\Models\User;
use Illuminate\Mail\Message;

class PasswordReMinderSendMailJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = '[sandwhichi] Find your password';
        $data = [
            "email" => $this->user->email
        ];

        try {
            Password::sendResetLink($data, function (Message $message) use ($subject) {
                $message->subject($subject);
            });
        } catch (\Exception $e) {
            Log::info('Mail Send Fail');
            // TODO :: mail code check
        }
    }
}
