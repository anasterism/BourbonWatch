<?php

namespace App\Models;

use App\Asterism\OHLQ\Client as OhlqClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Bourbon extends Model
{

    public function getAvailabilityAttribute()
    {
        $bourbonId = $this->id;

        $result = Cache::remember("ohlq-result.{$this->id}", 14400, function () use ($bourbonId) {
            return OhlqClient::fetch($bourbonId);
        });

        return $result->where('aquirable', true);
    }

}
