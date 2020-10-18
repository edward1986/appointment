<?php

namespace App\Console\Commands;

use App\Events\AppointmentDue;
use App\Jobs\SendMailJob;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an notification to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //One hour is added to compensate for PHP being one hour faster
        $now = date("Y-m-d H:i");

        $messages = Appointment::whereDate('date_string' ,'<=',  $now);
        if($messages->exists()){
            //Get all messages that their dispatch date is
            foreach ($messages->get() as $message) {
                if($message->delivered == 'NO')
                {
                    $message->delivered = 'YES';
                    $message->save();
                    AppointmentDue::dispatch();

                }
            }
        }
    }
}
