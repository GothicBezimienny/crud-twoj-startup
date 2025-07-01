<?php

namespace App\Models;

use Database\Factories\EmailFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    /** @use HasFactory<EmailFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
