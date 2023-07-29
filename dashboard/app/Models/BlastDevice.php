<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlastDevice extends Model
{
    use HasFactory;

    protected $table = "blastdevice";
    protected $guarded = [];
    
    public function device()
    {
        return $this->hasOne(Device::class, "id", "id_device");
    }
}
