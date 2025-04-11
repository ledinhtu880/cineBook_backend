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

        User::create([
            'first_name' => 'Tú',
            'last_name' => 'Lê Đình',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('6451389Tu'),
            'phone' => '0865176605',
            'city_id' => 1,
            'role' => 'admin',
        ]);

        for ($i = 0; $i <= 10; $i++) {
            User::create([
                'first_name' => 'dùng ' . $i,
                'last_name' => 'người',
                'email' => "user$i@gmail.com",
                'phone' => "012345678$i",
                'city_id' => 1,
                'password' => bcrypt('6451389Tu'),
            ]);
        }
    }
}
