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
            // CitySeeder::class,
            // CinemaSeeder::class,
            // GenreSeeder::class,
            // MovieSeeder::class,
            // RoomSeeder::class,
            // SeatSeeder::class,
            ProductSeeder::class,
            // ShowtimeSeeder::class,
        ]);

        /* User::create([
            'first_name' => 'Tú',
            'last_name' => 'Lê Đình',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('6451389Tu'),
            'phone' => '0865176605',
            'city_id' => 1,
            'role' => 1
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

        $cinemas = \Illuminate\Support\Facades\DB::table('cinemas')->get();
        $SEAT_TYPES = ['normal', 'vip', 'sweetbox'];
        $DAY_TYPES = ['weekday', 'weekend', 'holiday'];

        // Price configuration based on your requirements
        $pricingConfig = [
            'weekday' => [
                'normal' => 60000,
                'vip' => 70000,
                'sweetbox' => 120000
            ],
            'weekend' => [
                'normal' => 80000,
                'vip' => 90000,
                'sweetbox' => 130000
            ],
            'holiday' => [
                'normal' => 80000,
                'vip' => 90000,
                'sweetbox' => 130000
            ]
        ];

        foreach ($cinemas as $cinema) {
            foreach ($DAY_TYPES as $dayType) {
                foreach ($SEAT_TYPES as $seatType) {
                    \Illuminate\Support\Facades\DB::table('seat_prices')->insert([
                        'cinema_id' => $cinema->id,
                        'seat_type' => $seatType,
                        'day_type' => $dayType,
                        'price' => $pricingConfig[$dayType][$seatType],
                    ]);
                }
            }
        } */
    }
}
