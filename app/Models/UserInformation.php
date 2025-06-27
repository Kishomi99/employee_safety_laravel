<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserInformation extends Model
{
    protected $fillable = [
        'user_id', 
        'position', 
        'current_workplace', 
        'mobile_number', 
        'gender', 
        'profile_photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}