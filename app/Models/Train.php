<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    protected $table = 'trains';
    protected $fillable = ['lane_id', 'last_id', 'driver_id', 'direction'];
    protected $guarded = [];

    public function lane() {
        return $this->belongsTo('App\Models\Lane', 'lane_id', 'id');
    }

    public function last() {
        return $this->belongsTo('App\Models\Station', 'last_id', 'id');
    }

    public function driver() {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }
}
