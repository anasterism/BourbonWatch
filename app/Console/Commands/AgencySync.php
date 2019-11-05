<?php

namespace App\Console\Commands;

use \Storage;
use Illuminate\Console\Command;
use App\Models\Agency;

class AgencySync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:agencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Agency (Store) data.';

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
        $data  = Storage::get('agencies.json');
        $data  = json_decode($data);
        $count = count($data->agencies);

        Agency::truncate();

        $this->info("Importing {$count} agencies from agencies.json");
        $progress = $this->output->createProgressBar($count);

        foreach ($data->agencies as $agency)
        {
            $this->save($agency);
            $progress->advance();
        }

        $progress->finish();
        $this->info('');
    }

    public function save(array $agency)
    {
        $a = new Agency();

        $a->id        = $agency[0];
        $a->name      = $agency[1];
        $a->street    = $agency[2];
        $a->city      = $agency[3];
        $a->state     = $agency[4];
        $a->zip       = $agency[5];
        $a->phone     = $agency[6];
        $a->latitude  = $agency[7];
        $a->longitude = $agency[8];

        $a->save();
    }
}
