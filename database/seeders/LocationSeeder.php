<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/locations.sql');
        
        if (File::exists($path)) {
            try {
                $sql = File::get($path);
                
                // Disable foreign key checks to avoid issues with truncating/inserting
                Schema::disableForeignKeyConstraints();
                
                // Truncate the table first to avoid duplicate entry errors
                DB::table('locations')->truncate();
                
                DB::unprepared($sql);
                
                // Re-enable foreign key checks
                Schema::enableForeignKeyConstraints();
                
                // $this->command->info('Locations seeded successfully from SQL file.');
                echo "Locations seeded successfully.\n";
            } catch (\Exception $e) {
                file_put_contents('seeder_error.txt', $e->getMessage());
                echo "Error logged to seeder_error.txt\n";
                // Ensure FK checks are re-enabled even on error
                Schema::enableForeignKeyConstraints();
            }
        } else {
            // $this->command->error('locations.sql file not found: ' . $path);
            echo "File not found: $path\n";
        }
    }
}
