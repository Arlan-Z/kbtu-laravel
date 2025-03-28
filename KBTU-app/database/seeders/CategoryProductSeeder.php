<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Schema::disableForeignKeyConstraints();
//        DB::table('category_product')->truncate();
//        Product::truncate();
//        Category::truncate();
//        Schema::enableForeignKeyConstraints();

        $categories = Category::factory(10)->create();
        $categoryIds = $categories->pluck('id');
        Product::factory(50)
            ->create()
            ->each(function (Product $product) use ($categoryIds) {
                $categoriesToAttach = $categoryIds->random(rand(1, 3));
                $product->categories()->attach($categoriesToAttach);
            });
    }
}
