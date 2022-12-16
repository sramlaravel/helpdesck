<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'customers';

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
