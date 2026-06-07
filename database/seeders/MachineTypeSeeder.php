<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MachineType;
use Illuminate\Database\Seeder;

class MachineTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // EEC-10 | Midlight Duty Vehicles
            'EEC-10 | Midlight Duty Vehicles' => [
                ['category_code' => 'EEC 10-01', 'description' => 'Motor Cycle',        'eec_number' => 'EEC 10-01-001'],
                ['category_code' => 'EEC 10-02', 'description' => 'Automobile (Sedan)', 'eec_number' => 'EEC 10-02-001'],
                ['category_code' => 'EEC 10-03', 'description' => 'Station Wagon',      'eec_number' => 'EEC 10-03-001'],
                ['category_code' => 'EEC 10-04', 'description' => 'Single Cab Pick Up', 'eec_number' => 'EEC 10-04-001'],
                ['category_code' => 'EEC 10-05', 'description' => 'Double Cab Pick Up', 'eec_number' => 'EEC 10-05-001'],
                ['category_code' => 'EEC 10-06', 'description' => 'Mid Bus',            'eec_number' => 'EEC 10-06-001'],
                ['category_code' => 'EEC 10-07', 'description' => 'Mini Bus',           'eec_number' => 'EEC 10-07-001'],
                ['category_code' => 'EEC 10-08', 'description' => 'Medium Trucks',      'eec_number' => 'EEC 10-08-001'],
            ],

            // EEC-20 | Earth Moving / Heavy Machinery
            'EEC-20 | Earth Moving / Heavy Machinery' => [
                ['category_code' => 'EEC 20-01', 'description' => 'Dozer',              'eec_number' => 'EEC 20-01-001'],
                ['category_code' => 'EEC 20-02', 'description' => 'Excavator',          'eec_number' => 'EEC 20-02-001'],
                ['category_code' => 'EEC 20-03', 'description' => 'Wheel Loader',       'eec_number' => 'EEC 20-03-001'],
                ['category_code' => 'EEC 20-04', 'description' => 'Back Hoe Loader',    'eec_number' => 'EEC 20-04-001'],
                ['category_code' => 'EEC 20-05', 'description' => 'Grader',             'eec_number' => 'EEC 20-05-001'],
                ['category_code' => 'EEC 20-06', 'description' => 'Roller Compactor',   'eec_number' => 'EEC 20-06-001'],
                ['category_code' => 'EEC 20-07', 'description' => 'Scraper',            'eec_number' => 'EEC 20-07-001'],
                ['category_code' => 'EEC 20-08', 'description' => 'Wagon Drill',        'eec_number' => 'EEC 20-08-001'],
                ['category_code' => 'EEC 20-09', 'description' => 'Self Loading Mixer', 'eec_number' => 'EEC 20-09-001'],
            ],

            // EEC-30 | Heavy Duty Trucks
            'EEC-30 | Heavy Duty Trucks' => [
                ['category_code' => 'EEC 30-01', 'description' => 'Dump Truck',                    'eec_number' => 'EEC 30-01-001'],
                ['category_code' => 'EEC 30-02', 'description' => 'Low/High Bed Truck Tractor',    'eec_number' => 'EEC 30-02-001'],
                ['category_code' => 'EEC 30-03', 'description' => 'Water Truck',                   'eec_number' => 'EEC 30-03-001'],
                ['category_code' => 'EEC 30-04', 'description' => 'Fuel Truck',                    'eec_number' => 'EEC 30-04-001'],
                ['category_code' => 'EEC 30-05', 'description' => 'Carrier Truck',                 'eec_number' => 'EEC 30-05-001'],
                ['category_code' => 'EEC 30-06', 'description' => 'Mobile Workshop',               'eec_number' => 'EEC 30-06-001'],
                ['category_code' => 'EEC 30-07', 'description' => 'Service',                       'eec_number' => 'EEC 30-07-001'],
                ['category_code' => 'EEC 30-08', 'description' => 'Crane Truck Mounted',           'eec_number' => 'EEC 30-08-001'],
                ['category_code' => 'EEC 30-09', 'description' => 'Crane Mobile',                  'eec_number' => 'EEC 30-09-001'],
                ['category_code' => 'EEC 30-10', 'description' => 'Concrete Mixer',                'eec_number' => 'EEC 30-10-001'],
            ],

            // EEC-40 | Trailers
            'EEC-40 | Trailers' => [
                ['category_code' => 'EEC 40-01', 'description' => 'Low Bed Trailer',  'eec_number' => 'EEC 40-01-001'],
                ['category_code' => 'EEC 40-02', 'description' => 'High Bed Trailer', 'eec_number' => 'EEC 40-02-001'],
                ['category_code' => 'EEC 40-03', 'description' => 'Fuel Tank Trailer','eec_number' => 'EEC 40-03-001'],
            ],

            // EEC-50 | Plants
            'EEC-50 | Plants' => [
                ['category_code' => 'EEC 50-01', 'description' => 'Crusher Plant',            'eec_number' => 'EEC 50-01-001'],
                ['category_code' => 'EEC 50-02', 'description' => 'Concrete Batching Plant',  'eec_number' => 'EEC 50-02-001'],
                ['category_code' => 'EEC 50-03', 'description' => 'Asphalt Plant',            'eec_number' => 'EEC 50-03-001'],
                ['category_code' => 'EEC 50-04', 'description' => 'Tower Crane Plant',        'eec_number' => 'EEC 50-04-001'],
                ['category_code' => 'EEC 50-05', 'description' => 'Gantry Crane',             'eec_number' => 'EEC 50-05-001'],
                ['category_code' => 'EEC 50-06', 'description' => 'HCB Block Making Machine', 'eec_number' => 'EEC 50-06-001'],
            ],

            // EEC-60 | Asphalt Machines
            'EEC-60 | Asphalt Machines' => [
                ['category_code' => 'EEC 60-01', 'description' => 'Paver',                       'eec_number' => 'EEC 60-01-001'],
                ['category_code' => 'EEC 60-02', 'description' => 'Asphalt Ketel',               'eec_number' => 'EEC 60-02-001'],
                ['category_code' => 'EEC 60-03', 'description' => 'Asphalt Distributer Truck',   'eec_number' => 'EEC 60-03-001'],
                ['category_code' => 'EEC 60-04', 'description' => 'Pneumatic Roller',            'eec_number' => 'EEC 60-04-001'],
            ],

            // EEC-70 | Auxiliary Equipment
            'EEC-70 | Auxiliary Equipment' => [
                ['category_code' => 'EEC 70-01', 'description' => 'Generator',                    'eec_number' => 'EEC 70-01-001'],
                ['category_code' => 'EEC 70-02', 'description' => 'Compressor',                   'eec_number' => 'EEC 70-02-001'],
                ['category_code' => 'EEC 70-03', 'description' => 'Forklift',                     'eec_number' => 'EEC 70-03-001'],
                ['category_code' => 'EEC 70-04', 'description' => 'Water Pump',                   'eec_number' => 'EEC 70-04-001'],
                ['category_code' => 'EEC 70-05', 'description' => 'Mixer',                        'eec_number' => 'EEC 70-05-001'],
                ['category_code' => 'EEC 70-06', 'description' => 'Jack Hammer',                  'eec_number' => 'EEC 70-06-001'],
                ['category_code' => 'EEC 70-07', 'description' => 'Concrete Vibrator',            'eec_number' => 'EEC 70-07-001'],
                ['category_code' => 'EEC 70-08', 'description' => 'Hand Compactor',               'eec_number' => 'EEC 70-08-001'],
                ['category_code' => 'EEC 70-09', 'description' => 'Asphalt Cutter',               'eec_number' => 'EEC 70-09-001'],
                ['category_code' => 'EEC 70-10', 'description' => 'Road Marker',                  'eec_number' => 'EEC 70-10-001'],
                ['category_code' => 'EEC 70-11', 'description' => 'Fuel Dispensor',               'eec_number' => 'EEC 70-11-001'],
                ['category_code' => 'EEC 70-12', 'description' => 'Bar Cutting Machine',          'eec_number' => 'EEC 70-12-001'],
                ['category_code' => 'EEC 70-13', 'description' => 'Bar Bending Machine',          'eec_number' => 'EEC 70-13-001'],
                ['category_code' => 'EEC 70-14', 'description' => 'Construction Lift',            'eec_number' => 'EEC 70-14-001'],
                ['category_code' => 'EEC 70-15', 'description' => 'Drill',                        'eec_number' => 'EEC 70-15-001'],
                ['category_code' => 'EEC 70-16', 'description' => 'Power Float/Trowel Machine',   'eec_number' => 'EEC 70-16-001'],
            ],

            // EEC-80 | Farm Machineries
            'EEC-80 | Farm Machineries' => [
                ['category_code' => 'EEC 80-01', 'description' => 'Tractor',             'eec_number' => 'EEC 80-01-001'],
                ['category_code' => 'EEC 80-02', 'description' => 'Combine Harvester',   'eec_number' => 'EEC 80-02-001'],
                ['category_code' => 'EEC 80-03', 'description' => 'Thresher',            'eec_number' => 'EEC 80-03-001'],
                ['category_code' => 'EEC 80-04', 'description' => 'Corn Sheller',        'eec_number' => 'EEC 80-04-001'],
                ['category_code' => 'EEC 80-05', 'description' => 'Chemical Spray',      'eec_number' => 'EEC 80-05-001'],
                ['category_code' => 'EEC 80-06', 'description' => 'Water Spray',         'eec_number' => 'EEC 80-06-001'],
                ['category_code' => 'EEC 80-07', 'description' => 'Plow',               'eec_number' => 'EEC 80-07-001'],
                ['category_code' => 'EEC 80-08', 'description' => 'Planter',             'eec_number' => 'EEC 80-08-001'],
                ['category_code' => 'EEC 80-09', 'description' => 'Fertilizer Sprader',  'eec_number' => 'EEC 80-09-001'],
                ['category_code' => 'EEC 80-10', 'description' => 'Cultivator',          'eec_number' => 'EEC 80-09-001'],
            ],

            // EEC-90 | Workshop Tools
            'EEC-90 | Workshop Tools' => [
                ['category_code' => 'EEC 90-01', 'description' => 'Tool Box & Special Tools',          'eec_number' => 'EEC 90-01-001'],
                ['category_code' => 'EEC 90-02', 'description' => 'Grinder',                           'eec_number' => 'EEC 90-02-001'],
                ['category_code' => 'EEC 90-03', 'description' => 'Bench Vise',                        'eec_number' => 'EEC 90-03-001'],
                ['category_code' => 'EEC 90-04', 'description' => 'Oxygen Bottle',                     'eec_number' => 'EEC 90-04-001'],
                ['category_code' => 'EEC 90-05', 'description' => 'Aceytiline Bottle',                 'eec_number' => 'EEC 90-05-001'],
                ['category_code' => 'EEC 90-06', 'description' => 'Battery Tester',                    'eec_number' => 'EEC 90-06-001'],
                ['category_code' => 'EEC 90-07', 'description' => 'Electric Multi Meter',              'eec_number' => 'EEC 90-07-001'],
                ['category_code' => 'EEC 90-08', 'description' => 'Electrical Equipments Load Tester', 'eec_number' => 'EEC 90-08-001'],
                ['category_code' => 'EEC 90-09', 'description' => 'Hydraulic Lift Jack',               'eec_number' => 'EEC 90-09-001'],
                ['category_code' => 'EEC 90-10', 'description' => 'Mechanical Lift Jack',              'eec_number' => 'EEC 90-10-001'],
                ['category_code' => 'EEC 90-11', 'description' => 'Trolly Jack',                       'eec_number' => 'EEC 90-11-001'],
                ['category_code' => 'EEC 90-12', 'description' => 'Penumatic Grease Gun',              'eec_number' => 'EEC 90-12-001'],
                ['category_code' => 'EEC 90-13', 'description' => 'Tire Patch Iron',                   'eec_number' => 'EEC 90-13-001'],
                ['category_code' => 'EEC 90-14', 'description' => 'Chain Hoist',                       'eec_number' => 'EEC 90-14-001'],
                ['category_code' => 'EEC 90-15', 'description' => 'Injector Nozzle Tester',            'eec_number' => 'EEC 90-15-001'],
                ['category_code' => 'EEC 90-16', 'description' => 'Torque Wrench',                     'eec_number' => 'EEC 90-16-001'],
                ['category_code' => 'EEC 90-17', 'description' => 'Extractor',                         'eec_number' => 'EEC 90-17-001'],
                ['category_code' => 'EEC 90-18', 'description' => 'Pin Remover/Instoler, Hydraulic',   'eec_number' => 'EEC 90-18-001'],
                ['category_code' => 'EEC 90-19', 'description' => 'Sander',                            'eec_number' => 'EEC 90-19-001'],
                ['category_code' => 'EEC 90-20', 'description' => 'Air Compressor',                    'eec_number' => 'EEC 90-20-001'],
                ['category_code' => 'EEC 90-21', 'description' => 'Drill',                             'eec_number' => 'EEC 90-21-001'],
                ['category_code' => 'EEC 90-22', 'description' => 'Circular Saw',                      'eec_number' => 'EEC 90-22-001'],
            ],

            // EEC-A1 | Workshop Machines
            'EEC-A1 | Workshop Machines' => [
                ['category_code' => 'EEC A1-01', 'description' => 'Lathe Machine',              'eec_number' => 'EEC A1-01-001'],
                ['category_code' => 'EEC A1-02', 'description' => 'Milling Machine',            'eec_number' => 'EEC A1-02-001'],
                ['category_code' => 'EEC A1-03', 'description' => 'Honning Machine',            'eec_number' => 'EEC A1-03-001'],
                ['category_code' => 'EEC A1-04', 'description' => 'Wood Planner',               'eec_number' => 'EEC A1-04-001'],
                ['category_code' => 'EEC A1-05', 'description' => 'Valve Grinding Machine',     'eec_number' => 'EEC A1-05-001'],
                ['category_code' => 'EEC A1-06', 'description' => 'Radial Drill Machine',       'eec_number' => 'EEC A1-06-001'],
                ['category_code' => 'EEC A1-07', 'description' => 'Hydraulic Press Machine',    'eec_number' => 'EEC A1-08-001'],
                ['category_code' => 'EEC A1-08', 'description' => 'Welding Machine',            'eec_number' => 'EEC A1-08-001'],
                ['category_code' => 'EEC A1-09', 'description' => 'Drilling Machine',           'eec_number' => 'EEC A1-09-001'],
                ['category_code' => 'EEC A1-10', 'description' => 'Injection Pump Test Bench',  'eec_number' => 'EEC A1-11-001'],
                ['category_code' => 'EEC A1-11', 'description' => 'Battery Charger',            'eec_number' => 'EEC A1-12-001'],
            ],
        ];

        foreach ($data as $categoryName => $types) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                $this->command->warn("Category not found: {$categoryName}");
                continue;
            }

            foreach ($types as $type) {
                MachineType::firstOrCreate(
                    [
                        'category_id'   => $category->id,
                        'category_code' => $type['category_code'],
                    ],
                    [
                        'description' => $type['description'],
                        'eec_number'  => $type['eec_number'],
                    ]
                );
            }
        }

        $this->command->info('Machine types seeded successfully.');
    }
}
