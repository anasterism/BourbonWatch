<?php

namespace App\Asterism\OHLQ;

use App\Models\Agency;
use App\Models\Location;

class Result 
{

    public $agency    = null;
    public $locations = null;

    public function __construct(int $agencyId, string $stockLevel)
    {
        $this->agency    = Agency::find($agencyId);
        $this->locations = Location::orderBy('id', 'DESC')->get();

        if ($this->agency != null || !empty($this->locations)) {
            $this->processLocations();
        }
    }

    public function processLocations()
    {
        foreach ($this->locations as $index => $location)
        {
            $distance = $this->calculateDistance(
                $this->agency->latitude,
                $this->agency->longitude,
                $location->latitude,
                $location->longitude,
                'M'
            );
            
            $this->locations[$index]->distance = $distance;

            if($distance <= $location->search_radius) {
                $this->aquirable = true;
            }
        }

        $this->locations = $this->locations->sortBy('distance');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit) 
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