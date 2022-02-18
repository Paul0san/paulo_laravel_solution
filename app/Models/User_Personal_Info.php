<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Personal_Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'state',
        'country',
        'favorite_marvel_character',
        'favorite_marvel_comic',
    ];

    public function user(){
        // return $this->belongsTo(User_Personal_Info::class);
        return $this->belongsTo('App\Models\User');
    }
}
