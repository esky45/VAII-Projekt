<?php

use App\Models\Room3D;
use Illuminate\Database\Seeder;

class Rooms3DTableSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            [
                'name' => 'Living Room',
                'color' => '#4A90E2',
                'size' => ['x' => 8, 'y' => 5, 'z' => 6],
                'position' => ['x' => 0, 'y' => 0, 'z' => 0]
            ],
            [
                'name' => 'Kitchen',
                'color' => '#50E3C2',
                'size' => ['x' => 6, 'y' => 5, 'z' => 4],
                'position' => ['x' => 10, 'y' => 0, 'z' => 0]
            ],
            [
                'name' => 'Bedroom',
                'color' => '#E3507A',
                'size' => ['x' => 8, 'y' => 5, 'z' => 6],
                'position' => ['x' => 20, 'y' => 0, 'z' => 0]
            ]
        ];

        foreach ($rooms as $room) {
            Rooms3D::create($room);
        }
    }
}