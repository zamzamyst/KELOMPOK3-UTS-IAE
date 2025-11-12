<?php
namespace Database\Seeders;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder {
    public function run(): void {
        Schedule::create([
            'bus_id' => 1,
            'route_id' => 1,
            'departure_at' => now()->addDay(),
            'arrival_at' => now()->addDay()->addHours(4),
            'available_seats' => 30,
            'price' => 150000,
        ]);

        Schedule::create([
            'bus_id' => 2,
            'route_id' => 2,
            'departure_at' => now()->addDays(2),
            'arrival_at' => now()->addDays(2)->addHours(8),
            'available_seats' => 45,
            'price' => 250000,
        ]);

        Schedule::create([
            'bus_id' => 3,
            'route_id' => 3,
            'departure_at' => now()->addDays(3),
            'arrival_at' => now()->addDays(3)->addHours(12),
            'available_seats' => 50,
            'price' => 350000,
        ]);
    }
}
