<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use DB;

class WaveDeactivate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wave:deactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will be deactivate gogames wave subscriber';

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
        $subscribers = DB::select('select * from (select s.id, s.subscription_id,s.subscription_type_id,s.is_new_user,s.is_subscribed, s.is_active,time_to_sec(timediff(NOW(), NOW())) / 3600 as deactivate_time,u.id as user_id,u.plain_msisdn from tbl_subscribers as s join tbl_users as u on u.id=s.user_id where s.is_subscribed = 1 and s.is_active = 1) as tmp where tmp.deactivate_time = 0.0000 OR tmp.deactivate_time <= 0.0000');

        if ($subscribers) {
            $subscriberArr = array();
            foreach ($subscribers as $key => $subscriber) {
                $subscriberArr[] = $subscriber->id;
            }
            
            // foreach ($subscribers as $key => $subscriber) {
            //     $sub_row = SubscriberModel::find($subscriber->id);
            //     $sub_row->is_subscribed = 0;
            //     $sub_row->is_active = 0;
            //     $sub_row->save();

            //     $type_row = SubscriberLogModel::where('user_id', $subscriber->user_id)->get()->pluck('user_id');
            //     SubscriberLogModel::whereIn('user_id', $type_row)->update([
            //         'attempt_type' => 0,
            //         'attempt_type_status' => 0,
            //         'channel_id' => 6,
            //         'event' => 'UNSUBSCRIBED',
            //     ]);
            // }

        }
    }
}
