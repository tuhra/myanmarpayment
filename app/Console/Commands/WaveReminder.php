<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriberModel;
use DB;
use App\Helper\SmsHelper;

class WaveReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wave:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will be reminder to subscriber';

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
     * @return mixed
     */
    public function handle()
    {
        // $smshelper = new SmsHelper;
        // $reminders = DB::select('select s.*,time_to_sec(timediff(next_renewal_date, NOW())) / 3600 as reminder_time,u.id as user_id,u.plain_msisdn from tbl_subscribers as s join tbl_users as u on u.id=s.user_id where s.is_subscribed = 1 and s.is_active = 1');
        // if($reminders) {
        //     foreach ($reminders as $key => $reminder) {
        //         if ($reminder->reminder_time >= 24 && $reminder->reminder_time <= 25) {
        //             $smshelper->remindersms($reminder->user_id, $reminder->plain_msisdn, $reminder->id);
        //             // \Log::info('To Send Reminder');
        //         }
        //     }
        // }
    }
}
