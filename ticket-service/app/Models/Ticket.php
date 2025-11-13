<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model {
    use HasFactory;
    protected $fillable = [
        'ticket_number',
        'schedule_id',
        'passenger_name',
        'passenger_contact',
        'seat_count',
        'total_price',
        'status'
    ];
}