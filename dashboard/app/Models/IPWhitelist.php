<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPWhitelist extends Model
{
    use HasFactory;

    protected $table = "ipwhitelist";
    protected $guarded = [];
}
