<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Appointment extends Model
{
    use HasFactory, SearchableTrait;
    protected $searchable = [
        'columns' => [
            'appointments.title' => 1,
            'appointments.date_string' => 2,
            'users.name' => 5
        ],
        'joins' => [

            'users' => ['appointments.user_id', 'users.id'],
        ],
    ];
    protected $fillable = ['user_id', 'title', 'body',  'delivered', 'date_string', 'send_date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
