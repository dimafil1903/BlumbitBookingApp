<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class BookedMeeting extends Model
{

    protected $table = 'booked_meetings';
    protected $fillable = ['title', 'type', 'interval_id', 'email', 'phone', 'name', 'description', 'invites', 'custom_invites'];
}
