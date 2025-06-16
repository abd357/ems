<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeData extends Model
{
    protected $with = 'user';
    
    use HasFactory;

    protected $fillable = [
        'phone',
        'user_id',
        'joining_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
