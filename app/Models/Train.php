<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    protected $table = 'lanes';
    protected $fillable = ['lane_id', 'last_id', 'next_id', 'direction'];
    protected $guarded = [];

    public function lane() {
        return $this->belongsTo('App\Models\Lane', 'lane_id', 'id');
    }

    public function next() {
        return $this->belongsTo('App\Models\Station', 'next_id', 'id');
    }

    public function last() {
        return $this->belongsTo('App\Models\Station', 'last_id', 'id');
    }
}
