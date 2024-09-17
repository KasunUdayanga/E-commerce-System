<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoworkerPortfolio extends Model
{
    protected $table = 'coworker_portfolio';

    protected $fillable = ['coworker_id','image'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload') .'/'. $this->attributes['image'];
    }
}
