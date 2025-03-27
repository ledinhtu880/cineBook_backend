<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'ledinhtu880@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $this->call([
            CitySeeder::class,
            GenreSeeder::class,
            CinemaSeeder::class,
        ]);
    }
}
