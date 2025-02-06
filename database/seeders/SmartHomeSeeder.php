<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\DeviceType;

class SmartHomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Room::create(['name' => 'Living Room']);
    Room::create(['name' => 'Kitchen']);
    Room::create(['name' => 'Bedroom']);
    Room::create(['name' => 'Bathroom']);

    DeviceType::create(['type' => 'lightbulb', 'icon' => '💡']);
    DeviceType::create(['type' => 'sensor', 'icon' => '📟']);
}
}
