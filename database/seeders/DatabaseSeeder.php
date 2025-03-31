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
            'last_name' => 'Lê Đình',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('6451389'),
            'role' => 'admin',
        ]);


        User::create([
            'first_name' => 'dùng',
            'last_name' => 'người',
            'email' => 'user@gmail.com',
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
