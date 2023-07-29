<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    protected $table = "tags";
    protected $guarded = [];
    
    public function scopeAdmin($query){
        $user = \Auth::user();
        $query = $query->where('id_user', $user->id);
        
        return $query;
    }
}
