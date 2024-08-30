<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use GlobalStatus, Searchable;
    protected $casts = ['calling_codes' => 'array'];
    
    public function operators()
    {
        return $this->hasMany(Operator::class);
    }
}
