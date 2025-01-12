<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Produkt 1', 'description' => 'Opis produktu 1'],
            ['name' => 'Produkt 2', 'description' => 'Opis produktu 2'],
            ['name' => 'Produkt 3', 'description' => 'Opis produktu 3'],
            ['name' => 'Produkt 4', 'description' => 'Opis produktu 4'],
            ['name' => 'Produkt 5', 'description' => 'Opis produktu 5'],
            ['name' => 'Produkt 6', 'description' => 'Opis produktu 6'],
            ['name' => 'Produkt 7', 'description' => 'Opis produktu 7'],
            ['name' => 'Produkt 8', 'description' => 'Opis produktu 8'],
            ['name' => 'Produkt 9', 'description' => 'Opis produktu 9'],
            ['name' => 'Produkt 10', 'description' => 'Opis produktu 10'],
        ]);
    }
}
