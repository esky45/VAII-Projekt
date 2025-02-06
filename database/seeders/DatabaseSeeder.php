<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            Rooms3DSeeder::class,
        ]);

        $this->call([
            Rooms3DTableSeeder::class,
        ]);
        /*
        $this->call([
            SmartHomeSeeder::class,
        ]);
*/
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
