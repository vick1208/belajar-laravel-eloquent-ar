<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertNotNull;

class ProductTest extends TestCase
{
    public function testProduct()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::first();
        $this->get("/api/products/" . $product->id)
            ->assertStatus(200)
            ->assertHeader("X-Powered-By", "Programmer Bawang Putih")
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name,
                    ],
                    "price" => $product->price,
                    "is_expensive" => $product->price > 1000,
                    'created_at' => $product->created_at->toJSON(),
                    'updated_at' => $product->updated_at->toJSON(),
                ]
            ]);
    }

    public function testColWrap()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get('/api/products')->assertStatus(200)
            ->assertHeader("X-Powered-By", "Programmer Bawang Putih");
        $names = $response->json("data.*.name");

        for ($i = 0; $i < 5; $i++) {
            assertContains("product $i of Food", $names);
        }
        for ($i = 0; $i < 5; $i++) {
            assertContains("product $i of Travel", $names);
        }
    }

    public function testProductPaging()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get('/api/products-paging')->assertStatus(200);
        assertNotNull($response->json("links"));
        assertNotNull($response->json("meta"));
        assertNotNull($response->json("data"));
    }
    public function testAdditional()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();

        $response = $this->get("/api/products-debug/" . $product->id)
            ->assertStatus(200)
            ->assertJson([
                "author" => "Programmer Bawang Putih",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price,
                ]
            ]);

        assertNotNull($response->json("server_time"));
    }
}
