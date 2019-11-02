<?php

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'location_id'    => 1,
                'type'           => 'sms',
                'recipient'      => '937-207-1597',
                'recipient_name' => 'Master',
            ],
            [
                'location_id'    => 2,
                'type'           => 'sms',
                'recipient'      => '937-207-1597',
                'recipient_name' => 'Master',
            ],
            [
                'location_id'    => 3,
                'type'           => 'sms',
                'recipient'      => '937-207-1597',
                'recipient_name' => 'Master',
            ]
        ];

        foreach ($data as $notification)
        {
            $this->save($notification);
        }
    }

    public function save(array $notification)
    {
        $n = new Notification();
        $n->location_id    = $notification['location_id'];
        $n->type           = $notification['type'];
        $n->recipient      = $notification['recipient'];
        $n->recipient_name = $notification['recipient_name'];

        $n->save();
    }
}
