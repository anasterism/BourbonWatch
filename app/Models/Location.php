<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
