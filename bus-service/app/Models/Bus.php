<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model {
    use HasFactory;
    protected $fillable = ['plate_number','name','capacity','type'];
    public function schedules(){ return $this->hasMany(Schedule::class); }
}
