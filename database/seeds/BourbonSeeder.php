<?php

use Carbon\Carbon;
use App\Models\Bourbon;
use Illuminate\Database\Seeder;

class BourbonSeeder extends Seeder
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
                'id'    => '2880',
                'name'  => 'Eagle Rare',
                'image' => 'eagle_rare.png'
            ],
            [
                'id'    => '0911',
                'name'  => 'Blanton\'s',
                'image' => 'blantons.png'
            ],
            [
                'id'    => '2934',
                'name'  => 'E.H Taylor Small Batch',
                'image' => 'eh_taylor.png'
            ],
            [
                'id'    => '2923',
                'name'  => 'Elmer T. Lee',
                'image' => 'elmer_t_lee.png'
            ],
        ];

        foreach ($data as $bourbon)
        {
            $this->save($bourbon);
        }
    }

    private function save(array $bourbon)
    {
        $b            = new Bourbon();
        $b->id        = $bourbon['id'];
        $b->distillery = 'Buffalo Trace';
        $b->name      = $bourbon['name'];
        $b->image_url = $bourbon['image'];

        $b->save();
    }
}
