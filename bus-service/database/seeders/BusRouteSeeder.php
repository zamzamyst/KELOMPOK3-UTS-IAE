<?php
namespace Database\Seeders;
use App\Models\BusRoute;
use Illuminate\Database\Seeder;

class BusRouteSeeder extends Seeder {
    public function run(): void {
        BusRoute::create([
            'code' => 'RT001',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'stops' => ['Bogor', 'Ciawi'],
        ]);

        BusRoute::create([
            'code' => 'RT002',
            'origin' => 'Bandung',
            'destination' => 'Yogyakarta',
            'stops' => ['Garut', 'Tasikmalaya', 'Ciamis'],
        ]);

        BusRoute::create([
            'code' => 'RT003',
            'origin' => 'Jakarta',
            'destination' => 'Surabaya',
            'stops' => ['Cirebon', 'Semarang'],
        ]);
    }
}
