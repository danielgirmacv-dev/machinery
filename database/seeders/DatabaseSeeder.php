<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Location;
use App\Models\Machine;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create IT user
        User::create([
            'name' => 'IT User',
            'email' => 'it@example.com',
            'password' => Hash::make('password'),
            'role' => 'it',
            'is_active' => true,
        ]);

        // Create Viewer user
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'is_active' => true,
        ]);

        // Create categories using EEC classification
        $this->call(CategorySeeder::class);

        // Create machine types (sub-items per category)
        $this->call(MachineTypeSeeder::class);

        // Create departments
        $departments = [
            ['name' => 'Production', 'code' => 'PROD'],
            ['name' => 'Quality Control', 'code' => 'QC'],
            ['name' => 'Maintenance', 'code' => 'MAINT'],
            ['name' => 'Research & Development', 'code' => 'RND'],
            ['name' => 'Warehouse', 'code' => 'WH'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        // Create locations
        // $locations = [
        //     ['name' => 'Main Hall', 'building' => 'Building A', 'floor' => 'Ground Floor'],
        //     ['name' => 'Assembly Area', 'building' => 'Building A', 'floor' => '1st Floor'],
        //     ['name' => 'Testing Lab', 'building' => 'Building B', 'floor' => 'Ground Floor'],
        //     ['name' => 'Storage Room', 'building' => 'Building B', 'floor' => 'Basement'],
        //     ['name' => 'Workshop', 'building' => 'Building C', 'floor' => 'Ground Floor'],
        // ];

        // foreach ($locations as $location) {
        //     Location::create($location);
        // }
        
        $this->call(LocationSeeder::class);

        // Create sample machines
        $machines = [
            [
                'machine_code' => 'CNC-001',
                'machine_name' => 'CNC Turning Center',
                'category_id' => 1,
                'department_id' => 1,
                'location_id' => 1,
                'serial_number' => 'SN-2024-001',
                'status' => 'working',
                'purchase_date' => '2024-01-15',
                'remarks' => 'Primary CNC machine for production',
            ],
            [
                'machine_code' => 'LTH-001',
                'machine_name' => 'Manual Lathe',
                'category_id' => 2,
                'department_id' => 1,
                'location_id' => 1,
                'serial_number' => 'SN-2023-015',
                'status' => 'working',
                'purchase_date' => '2023-06-20',
                'remarks' => null,
            ],
            [
                'machine_code' => 'MIL-001',
                'machine_name' => 'Vertical Milling Machine',
                'category_id' => 3,
                'department_id' => 1,
                'location_id' => 3, // Changed from 2 to 3 as location 2 is missing in SQL
                'serial_number' => 'SN-2022-008',
                'status' => 'faulty',
                'purchase_date' => '2022-03-10',
                'remarks' => 'Spindle needs replacement',
            ],
            [
                'machine_code' => 'DRL-001',
                'machine_name' => 'Heavy Duty Drill Press',
                'category_id' => 4,
                'department_id' => 5,
                'location_id' => 5,
                'serial_number' => 'SN-2021-022',
                'status' => 'working',
                'purchase_date' => '2021-11-05',
                'remarks' => null,
            ],
            [
                'machine_code' => 'CMP-001',
                'machine_name' => 'Industrial Air Compressor',
                'category_id' => 5,
                'department_id' => 3,
                'location_id' => 4,
                'serial_number' => 'SN-2020-033',
                'status' => 'under_maintenance',
                'purchase_date' => '2020-08-18',
                'remarks' => 'Scheduled for annual maintenance',
            ],
        ];

        foreach ($machines as $machineData) {
            $machineData['created_by'] = $admin->id;
            $machineData['updated_by'] = $admin->id;
            Machine::create($machineData);
        }
    }
}
