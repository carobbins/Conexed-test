<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductImages;
use App\Http\Resources\Product\ProductCollection;


class ProductTest extends TestCase
{
    public function test_can_list_product() {
        
        $products =  factory(Product::class, 2)->create();

        $this->get(route('products'))
            ->assertStatus(200)
            ->assertJson($products->toArray())
            ->assertJsonStructure([
                '*' => [ 'id', 'name', 'description', 'price' ],
            ]);
    }
}
