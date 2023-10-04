<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // mass-assign values to these attributes only
    protected $fillable = ['company', 'position', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
