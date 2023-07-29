<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $table = "proxy";
    protected $guarded = [];

    public function scopeAdmin($query){
        // $user = \Auth::user();
        // if(!$user->role == 1){
        //     $query = $query->where('id_users', $user->id);
        // }
        // return $query;

        $user = \Auth::user();
        $users = User::where('id', $user->id)->orWhere('parent', $user->id)->get()->pluck("id");
        if($user->role != 1){
            $query->whereIn('device.id_users', $users);
        }
        return $query;
    }

    public function scopeProxy($query){
        // $query->where('expired',$item->expired);
        return $query;
    }


}
