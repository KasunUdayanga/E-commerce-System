<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $table = 'notification_template';

    protected $fillable = ['subject','title','notification_content','mail_content'];
}
