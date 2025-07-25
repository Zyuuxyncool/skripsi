<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelOccupancy extends Model
{
    protected $fillable = ['month', 'year', 'occupancy'];
    public $timestamps = false;

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'occupancy' => 'float',
    ];
}
