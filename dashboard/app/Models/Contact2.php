<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact2 extends Model
{
    use HasFactory;

    protected $table = "contact2";
    protected $primaryKey = "id";
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
}
