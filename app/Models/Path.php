<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    protected $table = 'paths';
    protected $fillable = ['lane_id', 'station1_id', 'station2_id', 'time'];
    protected $guarded = [];

    public function lane() {
        return $this->belongsTo('App\Models\Lane', 'lane_id', 'id');
    }

    public function station1() {
        return $this->belongsTo('App\Models\Station', 'station1_id', 'id');
    }

    public function station2() {
        return $this->belongsTo('App\Models\Station', 'station2_id', 'id');
    }
}
