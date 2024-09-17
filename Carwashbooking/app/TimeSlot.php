<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = 'timeslot';

    protected $fillable = ['timeslot_id','createddate','time'];
}
