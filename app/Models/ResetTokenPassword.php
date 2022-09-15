<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetTokenPassword extends Model
{
    use HasFactory;
    protected $table = 'reset_token_passwords';

    protected $fillable = [
        'email',
        'token',
        'link',
        'created_at',
    ];
}
