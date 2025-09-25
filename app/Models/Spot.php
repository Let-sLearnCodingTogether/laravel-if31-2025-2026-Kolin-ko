<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spot extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'user_id',
        'name',
        'address',
        'picture'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->hasMany(Category::class);
    }
    
    public function review(){
        return $this->hasMany(Category::class);
    }
}
