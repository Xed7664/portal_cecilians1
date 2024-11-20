<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLock extends Model
{
    protected $fillable = ['user_id', 'device_token', 'failed_attempts', 'locked_at'];
}