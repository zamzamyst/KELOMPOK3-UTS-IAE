<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model {
    use HasFactory;
    protected $fillable = ['bus_id','route_id','departure_at','arrival_at','available_seats','price'];
    public function bus(){ return $this->belongsTo(Bus::class); }
    public function route(){ return $this->belongsTo(BusRoute::class, 'route_id'); }
}
