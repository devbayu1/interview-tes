<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_name',
        'provider_id',
        'user_id'
    ];
}
