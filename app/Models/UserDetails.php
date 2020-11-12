<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'phone', 'age','user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
