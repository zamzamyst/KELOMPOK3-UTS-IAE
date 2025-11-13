<?php
namespace Database\Seeders;
use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder {
    public function run(): void {
        Bus::create([
            'plate_number' => 'B 1238 CD',
            'name' => 'Bus Prima Jasa',
            'capacity' => 30,
            'type' => 'Ekonomi',
            'route_id' => 1,
        ]);

        Bus::create([
            'plate_number' => 'B 2345 EF',
            'name' => 'Bus Pariwisata',
            'capacity' => 45,
            'type' => 'Eksekutif',
            'route_id' => 2,
        ]);

        Bus::create([
            'plate_number' => 'B 3456 GH',
            'name' => 'Bus Malam',
            'capacity' => 50,
            'type' => 'Sleeper',
            'route_id' => 3,
        ]);
    }
}
