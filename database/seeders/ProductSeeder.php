<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { // Seed products
        $products = [
            [
                "name" => "Bắp rang bơ",
                "description" => "Bắp rang bơ giòn tan, thơm ngon",
                "price" => 50000,
                "category" => "food",
                "image" => "popcorn.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Xúc xích",
                "description" => "Xúc xích nướng đậm vị",
                "price" => 30000,
                "category" => "food",
                "image" => "sausage.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Khoai tây chiên",
                "description" => "Khoai tây chiên giòn rụm",
                "price" => 40000,
                "category" => "food",
                "image" => "fries.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Coca Cola",
                "description" => "Nước ngọt có ga sảng khoái",
                "price" => 25000,
                "category" => "beverage",
                "image" => "coca.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Trà sữa",
                "description" => "Trà sữa trân châu thơm ngon",
                "price" => 45000,
                "category" => "beverage",
                "image" => "milktea.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Nước suối",
                "description" => "Nước suối tinh khiết",
                "price" => 20000,
                "category" => "beverage",
                "image" => "water.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];
        DB::table('products')->insert($products);

        // Seed product combos
        $combo1 = DB::table('product_combos')->insertGetId([
            "name" => "Combo bắp + nước",
            "description" => "1 bắp rang bơ + 1 nước ngọt",
            "price" => 70000,
            "image" => "combo1.jpg",
            "is_active" => true,
            "created_at" => now(),
            "updated_at" => now()
        ]);
        $combo2 = DB::table('product_combos')->insertGetId([
            "name" => "Combo đặc biệt",
            "description" => "1 bắp rang bơ + 2 nước + 1 xúc xích",
            "price" => 120000,
            "image" => "combo2.jpg",
            "is_active" => true,
            "created_at" => now(),
            "updated_at" => now()
        ]);

        DB::table('product_combo_items')->insert([
            [
                "product_combo_id" => $combo1,
                "product_id" => 1,
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $combo1,
                "product_id" => 4,
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $combo2,
                "product_id" => 1,
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $combo2,
                "product_id" => 4,
                "quantity" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $combo2,
                "product_id" => 2,
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
