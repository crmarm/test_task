<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CreateProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = [
            [
                'name'=>'sour cream',
                'price'=>'400',
                'quantity'=>'7',
                'expiration_date'=>  Carbon::now()->addDays(5),
            ],
            [
                'name'=>'bread',
                'price'=>'200',
                'quantity'=>'8',
                'expiration_date'=>  Carbon::now()->addDays(2),
            ],
            [
                'name'=>'fish',
                'price'=>'3000',
                'quantity'=>'4',
                'expiration_date'=>  Carbon::now()->addDays(20),
            ],
        ];

        foreach ($product as $key => $value) {
            Product::create($value);
        }
    }
}
