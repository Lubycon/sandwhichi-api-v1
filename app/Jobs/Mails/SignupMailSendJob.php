<?php

namespace App\Jobs\Mails;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use Abort;
use Mail;
use Log;

class SignupMailSendJob extends Job implements ShouldQueue
{
    use InteractsWithQueue,
        SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info('mail send start');

        $this->user->createSignupToken();

        $to = $this->user->email;
        $subject = '[sandwhichi] Account Success to sandwhichi';
        $data = [
            'user' => $this->user,
            'token' => $this->user->getSignupToken()
        ];

        try{
            Mail::send("emails.signup", $data, function($message) use($to, $subject) {
                $message->to($to)->subject($subject);
            });
        }catch(\Exception $e){
            Log::info('Mail Send Fail');
            // TODO :: mail code check
        }

        Log::info('mail sended');
    }
}
