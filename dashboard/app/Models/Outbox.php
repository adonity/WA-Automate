<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbox extends Model
{
    use HasFactory;
    protected $table = "outbox";
    protected $guarded = [];
    
    public function scopeAdmin($query)
    {
        // $user = \Auth::user();
        // if(!$user->admin == 1){
        //     $query = $query->where('device.id_users', $user->id);
        // }
        // return $query;
        
        $user = \Auth::user();
        $users = User::where('id', $user->id)->orWhere('parent', $user->id)->get()->pluck("id");
        if($user->role != 1){
            $query->whereIn('device.id_users', $users);
        }
        return $query;
    }

    public function scopeDeviceKey($query, $key)
    {
        if($key){
            $query = $query->where('device.key', "=", $key);
        }
        return $query;
    }

    public function scopeSearch($query, $q)
    {
        if($q){
            $query = $query->where(function ($query2) use ($q){
                    $query2->where($this->table.'.number', 'like', '%' . $q . '%')
                    ->orWhere($this->table.'.text', 'like', '%' . $q . '%');
                });
        }
        return $query;
    }

    public function scopeStatus($query, $status)
    {
        if($status != null){
            $query = $query->where($this->table.'.status', "=", (Int) $status);
        }
        return $query;
    }

    public function scopeTeam($query){
        return $query
                ->leftJoin(\DB::raw('users owner'),'owner.id','device.id_users')
                ->leftJoin(\DB::raw('users parent'),'owner.parent','parent.id');
    }
}
