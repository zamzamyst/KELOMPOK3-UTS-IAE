<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tracking extends Model {
    use HasFactory;
    protected $fillable = ['bus_id','lat','lng'];
}
