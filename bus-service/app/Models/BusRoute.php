<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusRoute extends Model {
    use HasFactory;
    protected $table = 'routes';
    protected $fillable = [
        'code',
        'origin',
        'destination',
        'stops'
    ];
    
    protected $casts = ['stops' => 'array'];
    
    public function schedules()
    { 
        return $this->hasMany(Schedule::class, 'route_id'); 
    }

    public function buses()
    { 
        return $this->hasMany(Bus::class, 'route_id'); 
    }
}
