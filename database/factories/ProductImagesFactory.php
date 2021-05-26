<?php

namespace Database\Factories;

use App\Models\ProductImages;
use Illuminate\Database\Eloquent\Factories\Factory;
//use Faker\Factory as Faker;

class ProductImagesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductImages::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'product_id' => Product::all()->random()->id,
            'image_path' => $this->faker->image(public_path('images'),640,480, null, false)
        ];
    }
}
