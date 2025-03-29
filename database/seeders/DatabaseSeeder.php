<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Tú',
            'last_name' => 'Le Dinh',
            'email' => 'admin',
            'password' => bcrypt('6451389'),
        ]);

        $this->call([
            CitySeeder::class,
            CinemaSeeder::class,
            GenreSeeder::class,
            MovieSeeder::class,
            RoomSeeder::class,
            SeatSeeder::class,
            ProductSeeder::class,
            ShowtimeSeeder::class,
        ]);
    }
}
