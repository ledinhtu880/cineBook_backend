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
    {
        // Seed products
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
                "name" => "Pepsi",
                "description" => "Nước ngọt có ga sảng khoái",
                "price" => 25000,
                "category" => "beverage",
                "image" => "pepsi.jpg",
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
            ],
            [
                "name" => "Snack",
                "description" => "Snack đa dạng, giòn rụm",
                "price" => 35000,
                "category" => "food",
                "image" => "snack.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];
        DB::table('products')->insert($products);

        // Seed product combos from blade file
        $combos = [
            [
                "name" => "Combo 1 Big",
                "description" => "\"Thỏa mãn cơn thèm\" với 1 phần bắp rang bơ thơm ngon và 1 Pepsi mát lạnh!",
                "price" => 89000,
                "image" => "storage/products/combo_big.jpg",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Combo 1 Big Extra",
                "description" => "\"Thỏa mãn cơn thèm\" với 1 phần bắp rang bơ thơm ngon, 1 Pepsi mát lạnh và 1 gói snack tuỳ chọn!",
                "price" => 109000,
                "image" => "storage/products/combo_big_extra.jpg",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Combo 2 Big",
                "description" => "\"Nhân đôi sự sảng khoái! Combo 2 gồm 1 bắp rang bơ lớn, 2 Pepsi cỡ lớn – tiết kiệm hơn 28,000!",
                "price" => 109000,
                "image" => "storage/products/combo_2_big.jpg",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Combo 2 Big Extra",
                "description" => "\"Nhân đôi sự sảng khoái! Combo 2 gồm 1 bắp rang bơ lớn, 2 Pepsi cỡ lớn + 1 snack tuỳ chọn– tiết kiệm hơn 33,000!",
                "price" => 129000,
                "image" => "storage/products/combo_2_big.jpg", // Using the same image as specified
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Combo Friends 1 Big",
                "description" => "\"Chia sẻ niềm vui với bạn bè! Friend Combo 1 gồm 1 bắp rang bơ, 3 Pepsi mát lạnh và 1 món snack tự chọn – tiết kiệm hơn 52,000!",
                "price" => 149000,
                "image" => "storage/products/combo_friends_1_big.jpg",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Combo Friends 2 Big",
                "description" => "\"Thêm bạn, thêm vui! Friend Combo 2 mang đến 2 bắp rang bơ, 4 Pepsi mát lạnh và 2 món snack tự chọn – tiết kiệm hơn 95,000!",
                "price" => 229000,
                "image" => "storage/products/combo_friend_2_big.jpg",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];

        $comboIds = [];
        foreach ($combos as $combo) {
            $comboIds[] = DB::table('product_combos')->insertGetId($combo);
        }

        // Define combo items for each combo
        $comboItems = [
            // Combo 1 Big
            [
                "product_combo_id" => $comboIds[0],
                "product_id" => 1, // Bắp rang bơ
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[0],
                "product_id" => 4, // Pepsi
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],

            // Combo 1 Big Extra
            [
                "product_combo_id" => $comboIds[1],
                "product_id" => 1, // Bắp rang bơ
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[1],
                "product_id" => 4, // Pepsi
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[1],
                "product_id" => 7, // Snack
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],

            // Combo 2 Big
            [
                "product_combo_id" => $comboIds[2],
                "product_id" => 1, // Bắp rang bơ
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[2],
                "product_id" => 4, // Pepsi
                "quantity" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ],

            // Combo 2 Big Extra
            [
                "product_combo_id" => $comboIds[3],
                "product_id" => 1, // Bắp rang bơ
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[3],
                "product_id" => 4, // Pepsi
                "quantity" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[3],
                "product_id" => 7, // Snack
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],

            // Combo Friends 1 Big
            [
                "product_combo_id" => $comboIds[4],
                "product_id" => 1, // Bắp rang bơ
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[4],
                "product_id" => 4, // Pepsi
                "quantity" => 3,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[4],
                "product_id" => 7, // Snack
                "quantity" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],

            // Combo Friends 2 Big
            [
                "product_combo_id" => $comboIds[5],
                "product_id" => 1, // Bắp rang bơ
                "quantity" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[5],
                "product_id" => 4, // Pepsi
                "quantity" => 4,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "product_combo_id" => $comboIds[5],
                "product_id" => 7, // Snack
                "quantity" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];

        DB::table('product_combo_items')->insert($comboItems);
    }
}