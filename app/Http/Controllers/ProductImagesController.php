<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\Product;

class ProductImagesController extends Controller
{
    public function store(Request $request, $product_id)
    {
        $reponse_message = '';
        $product = Product::find($product_id);
        if(!$product)
        {
            return ['message' => 'Product not found'];
        }
        if($request->hasFile('images'))
        {
            $imageFiles = $request->file('images');
            $imageRules = ['image' => 'mimes:jpeg,jpg,png,gif|required|max:4096'];
            foreach($imageFiles as $image) {
                $validator = Validator::make(['image' => $image],$imageRules);
                if($validator->fails()) {
                   $reponse_message  .= $validator->errors()->getMessage(); 
                }
                else {
                    $path = $image->store(public_path('images'));
                    $productImage = ProductImages::create([
                        'product_id' => $product_id,
                        'image_path' => $path
                    ]);
                }
            }
        }
        
        if(isset($reponse_message ) === true && $reponse_message  === '') {
            $reponse_message = 'Upload Success';
        }
        return ['message' => $reponse_message];
        
    }
    
    public function destroy(Product $product, ProductImage $productImage)
    {
        if($productImage->image_path)
        {
            Storage::delete($productImage->image_path);
        }
        return $productImage->destroy();
    }
}
