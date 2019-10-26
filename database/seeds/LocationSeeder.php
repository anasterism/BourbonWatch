<?php

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
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
                'name'     => "Home",
                'street'   => '4036 S. Tecumseh Rd.',
                'city'     => 'Springfield',
                'state'    => 'OH',
                'zip'      => '45502',
                'latitude' => '39.869081',
                'logitude' => '-83.889457',
            ],
            [
                'name'     => "Justine's condo",
                'street'   => '108 Marco Ln.',
                'city'     => 'Washington Township',
                'state'    => 'OH',
                'zip'      => '45458',
                'latitude' => '39.607306',
                'logitude' => '-84.156642',
            ],
            [
                'name'     => "Work",
                'street'   => '1202 E. Dayton-Yellow Springs Rd.',
                'city'     => 'Fairborn',
                'state'    => 'OH',
                'zip'      => '45324',
                'latitude' => '39.783138',
                'logitude' => '-83.995284',
            ],
        ];

        foreach ($data as $location)
        {
            $this->save($location);
        }
    }

    public function save(array $location)
    {
        $l = new Location();
        $l->name = $location['name'];
        $l->street = $location['street'];
        $l->city = $location['city'];
        $l->state = $location['state'];
        $l->zip = $location['zip'];
        $l->latitude = $location['latitude'];
        $l->longitude = $location['logitude'];

        $l->save();
    }
}
