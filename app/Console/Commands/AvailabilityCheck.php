<?php

namespace App\Console\Commands;

use App\Models\Bourbon;
use App\Models\Agency;
use App\Models\Location;
use App\Models\Notification;
use Twilio\Rest\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AvailabilityCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bourbon:availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



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
        $bourbons = Bourbon::all();

        foreach ($bourbons as $bourbon)
        {
            $lastRun = Cache::get("availability.lastRun.{$bourbon->id}", []);

            foreach ($bourbon->availability as $result)
            {
                if (empty($lastRun) || !in_array($result->agency->id, $lastRun)) {
                    $this->processNotifications($bourbon->name, $result);
                }
            }
            
            Cache::put("availability.lastRun.{$bourbon->id}", explode(',', $bourbon->availability->implode('agency.id',',')));
        }
    }

    public function processNotifications(string $bourbon, $result)
    {
        foreach ($result->locations as $location)
        {
            if ($location->distance <= $location->search_radius) {
                foreach ($location->notifications as $n) {
                    $this->{$n->type}($bourbon, $result->agency, $location, $n);
                }
            }
        }
    }

    public function sms(string $bourbonName, Agency $agency, Location $location, Notification $notification)
    {
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->messages->create(
            $this->normalizeNumber($notification->recipient),
            [
                'from' => env('TWILIO_NUMBER'),
                'body' => "{$notification->recipient_name}! Bourbon aquired! {$bourbonName} was spotted at {$agency->name}, {$agency->street} {$agency->city}, {$agency->state} {$agency->zip}. Only {$location->distance} miles from {$location->name}"
            ]
        );
        Log::info("SMS SENT! {$notification->recipient_name}! Bourbon aquired! {$bourbonName} was spotted at {$agency->name}, {$agency->street} {$agency->city}, {$agency->state} {$agency->zip}. Only {$location->distance} miles from {$location->name}");
    }

    private function normalizeNumber($number) {
        $number = trim($number);
        $number = preg_replace("/[^A-Za-z0-9]/", '', $number);
        $number = preg_replace("/\\d{10}$/u", ",+1$0", $number);
        $number = explode(',', $number);
        return $number[1];
    }
}
