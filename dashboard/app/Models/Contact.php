<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = "contact";
    protected $primaryKey = "id";
    protected $guarded = [];
    
    public function scopeAdmin($query){
        $user = \Auth::user();
        $users = User::where('id', $user->id)->orWhere('parent', $user->id)->get()->pluck("id");
        if($user->role != 1){
            $query->whereIn('id_user', $users);
        }
        return $query;
    }

    public function scopeContact1($query){
        $query->where('type', 1);
        return $query;
    }
    
    public function scopeContact2($query){
        $query->where('type', 2);
        return $query;
    }
}
