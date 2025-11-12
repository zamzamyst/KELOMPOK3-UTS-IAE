<?php
namespace Database\Seeders;
use App\Models\Tracking;
use Illuminate\Database\Seeder;

class TrackingSeeder extends Seeder {
    public function run(): void {
        Tracking::create([
            'bus_id' => 1,
            'schedule_id' => 1,
            'lat' => -6.914744,
            'lng' => 107.60981,
        ]);

        Tracking::create([
            'bus_id' => 2,
            'schedule_id' => 2,
            'lat' => -7.250445,
            'lng' => 107.700504,
        ]);

        Tracking::create([
            'bus_id' => 3,
            'schedule_id' => 3,
            'lat' => -7.797068,
            'lng' => 110.370529,
        ]);
    }
}
