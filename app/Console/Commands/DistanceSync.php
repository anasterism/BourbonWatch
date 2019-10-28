<?php

namespace App\Console\Commands;

use App\Models\Agency;
use App\Models\Location;
use App\Models\Distance;
use Illuminate\Console\Command;

class DistanceSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:distances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and store distances from saved locations to angency locations.';

    private $agencies;
    private $locations;

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
        \DB::query("TRUNCATE TABLE distances");

        $this->agencies  = Agency::get();
        $this->locations = Location::get();

        $progress = $this->output->createProgressBar($this->locations->count());

        foreach ($this->locations as $location) {
            $this->findAgencyDistance($location);
            
            $progress->advance();
        }

        $progress->finish();
        $this->info('');
    }

    public function findAgencyDistance(Location $location)
    {
        foreach ($this->agencies as $agency) {
            $d = new Distance();
            $d->agency_id   = $agency->id;
            $d->location_id = $location->id;
            $d->miles       = $this->distance($location->latitude, $location->longitude, $agency->latitude, $agency->longitude, 'M');
            
            $d->save();
        }
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit) 
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist  = acos($dist);
            $dist  = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit  = strtoupper($unit);
            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
      
}
