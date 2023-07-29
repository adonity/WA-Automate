<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blast extends Model
{
    use HasFactory;

    protected $table = "blast";
    protected $guarded = [];

    public function scopeAdmin($query){
        // $user = \Auth::user();
        // if(!$user->admin == 1){
        //     $query = $query->where('id_user', $user->id);
        // }
        // return $query;
        
        $user = \Auth::user();
        $users = User::where('id', $user->id)->orWhere('parent', $user->id)->get()->pluck("id");
        if($user->role != 1){
            $query->whereIn('id_user', $users);
        }
        return $query;
    }
    
    public function blastcontact()
    {
        return $this->hasMany(BlastContact::class, "id_blast", "id");
    }

    public function blastdevice()
    {
        return $this->hasMany(BlastDevice::class, "id_blast", "id");
    }

    public function type()
    {
        return $this->hasOne(MessageType::class, "id", "id_type");
    }
}
