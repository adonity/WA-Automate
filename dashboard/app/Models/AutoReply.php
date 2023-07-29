<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
    use HasFactory;

    protected $table = "autoreply";
    protected $guarded = [];
    
    public function scopeAdmin($query)
    {
        // $user = \Auth::user();
        // if(!$user->admin == 1){
        //     $query = $query->where('device.id_users', $user->id)->orWhereNull('device.id_users');
        // }
        // return $query;
        
        $user = \Auth::user();
        $users = User::where('id', $user->id)->orWhere('parent', $user->id)->get()->pluck("id");
        if(!$user->role == 1){
            $query->whereIn('device.id_users', $users)->orWhereNull('device.id_users');
        }
        return $query;
    }

    public function type()
    {
        return $this->hasOne(MessageType::class, "id", "id_type");
    }
    public function scopeDeviceKey($query, $key)
    {
        if($key){
            $query = $query->where('device.key', "=", $key);
        }
        return $query;
    }
}
