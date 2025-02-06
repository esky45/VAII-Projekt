<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rooms3DSeeder extends Seeder
{
    public function run()
    {
        // Insert sample rooms
        DB::table('rooms3d')->insert([
            [
                'name' => 'Living Room',
                'color' => '#FF5733',
                'size' => json_encode(['x' => 5, 'y' => 3, 'z' => 4]),
                'position' => json_encode(['x' => 0, 'y' => 0, 'z' => 0]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bedroom',
                'color' => '#C70039',
                'size' => json_encode(['x' => 4, 'y' => 3, 'z' => 3]),
                'position' => json_encode(['x' => 10, 'y' => 0, 'z' => 0]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kitchen',
                'color' => '#900C3F',
                'size' => json_encode(['x' => 6, 'y' => 3, 'z' => 5]),
                'position' => json_encode(['x' => 5, 'y' => 0, 'z' => 0]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}