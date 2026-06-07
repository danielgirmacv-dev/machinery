<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'EEC-10 | Midlight Duty Vehicles',
                'description' => 'EEC-10: Midlight Duty Vehicles — Motor Cycle, Automobile, Station Wagon, Pick Up, Bus, Trucks',
            ],
            [
                'name' => 'EEC-20 | Earth Moving / Heavy Machinery',
                'description' => 'EEC-20: Earth Moving Machines / Heavy Machinery — Dozer, Excavator, Wheel Loader, Back Hoe Loader, Grader, Roller Compactor, Scraper, Wagon Drill, Self Loading Mixer',
            ],
            [
                'name' => 'EEC-30 | Heavy Duty Trucks',
                'description' => 'EEC-30: Heavy Duty Trucks — Dump Truck, Truck Tractor, Water Truck, Fuel Truck, Carrier Truck, Mobile Workshop, Service, Crane Truck, Crane Mobile, Concrete Mixer',
            ],
            [
                'name' => 'EEC-40 | Trailers',
                'description' => 'EEC-40: Trailers — Low Bed Trailer, High Bed Trailer, Fuel Tank Trailer',
            ],
            [
                'name' => 'EEC-50 | Plants',
                'description' => 'EEC-50: Plants — Crusher Plant, Concrete Batching Plant, Asphalt Plant, Tower Crane Plant, Gantry Crane, HCB Block Making Machine',
            ],
            [
                'name' => 'EEC-60 | Asphalt Machines',
                'description' => 'EEC-60: Asphalt Machines — Paver, Asphalt Ketel, Asphalt Distributer Truck, Pneumatic Roller',
            ],
            [
                'name' => 'EEC-70 | Auxiliary Equipment',
                'description' => 'EEC-70: Auxiliary Equipment — Generator, Compressor, Forklift, Water Pump, Mixer, Jack Hammer, Concrete Vibrator, Hand Compactor, Asphalt Cutter, Road Marker, Fuel Dispensor, Bar Cutting/Bending Machine, Construction Lift, Drill, Power Float/Trowel Machine',
            ],
            [
                'name' => 'EEC-80 | Farm Machineries',
                'description' => 'EEC-80: Farm Machineries — Tractor, Combine Harvester, Thresher, Corn Sheller, Chemical Spray, Water Spray, Plow, Planter, Fertilizer Sprader, Cultivator',
            ],
            [
                'name' => 'EEC-90 | Workshop Tools',
                'description' => 'EEC-90: Workshop Tools — Tool Box, Grinder, Bench Vise, Oxygen Bottle, Acetyline Bottle, Battery Tester, Electric Multi Meter, Load Tester, Hydraulic/Mechanical Lift Jack, Trolly Jack, Pneumatic Grease Gun, Tire Patch Iron, Chain Hoist, Injector Nozzle Tester, Torque Wrench, Extractor, Pin Remover, Sander, Air Compressor, Drill, Circular Saw',
            ],
            [
                'name' => 'EEC-A1 | Workshop Machines',
                'description' => 'EEC-A1: Workshop Machines — Lathe Machine, Milling Machine, Honning Machine, Wood Planner, Valve Grinding Machine, Radial Drill Machine, Hydraulic Press Machine, Welding Machine, Drilling Machine, Injection Pump Test Bench, Battery Charger',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
