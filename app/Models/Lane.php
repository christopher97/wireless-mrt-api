<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lane extends Model
{
   	protected $table = 'lanes';
    protected $fillable = ['name'];
    protected $guarded = [];
}
