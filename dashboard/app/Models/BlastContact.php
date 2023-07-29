<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlastContact extends Model
{
    use HasFactory;
    
    protected $table = "blastcontact";
    protected $guarded = [];
    
    public function contact()
    {
        return $this->hasOne(Contact::class, "id", "id_contact");
    }
    
    public function device()
    {
        return $this->hasOne(Device::class, "id", "id_device");
    }
}
