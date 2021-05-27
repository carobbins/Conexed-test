<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImages;
use App\Http\Resources\Product\ProductImagesResource;
use Validator;
class ProductImagesController extends Controller
{
    public function index(Product $product)
    {
        return ProductImagesResource::collection($product->images);
    }

    public function show(ProductImage $productImage)
    {
        $path = public_path('images/'.$productImage->image_filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function store(Request $request, $product_id)
    {
        $success = false;
        $product = Product::find($product_id);
        if(!$product) {
            return ['success' => $success, 'message' => 'Product not found'];
        }
        $reponse_message = '';
        if($imageFiles = $request->file('images')) {
            $reponse_message = 'Image files exist '.$request->file('images').length;
            //return ['success' => $success, 'message' => $reponse_message,'imageFiles' => $imageFiles];
        }
        else {
            return ['success' => $success, 'message' => 'No Image Files','imageFiles' => $imageFiles];
        }
        $imageRules = ['image' => 'mimes:jpeg,jpg,png,gif|required|max:4096'];
        // foreach($imageFiles as $image) {
        //     $validator = Validator::make(['image' => $image],$imageRules);
        //     if($validator->fails()) {
        //         $reponse_message  .= $validator->errors()->getMessage(); 
        //     }
        //     else {
        //         $path = $image->store(public_path('images'));
        //         $reponse_message .= " File stored: ".$path;
        //         $productImage = ProductImages::create([
        //             'product_id' => $product_id,
        //             'image_filename' => $path
        //         ]);
        //         $success = true; //at least one file has to upload for a success if we have files
        //     }
        // }
       
        foreach($request->file('images') as $file){

        $path = $file->store(public_path('images/'));
        $reponse_message .= " File stored: ".$path;
        $productImage = ProductImages::create([
            'product_id' => $product_id,
            'image_filename' => basename($path)
        ]);
        $success = true;
        }
        return ['success' => $success, 'message' => $reponse_message];
        // if($request->hasFile('images'))
        // {
        //     $imageFiles = $request->file('images');
        //     $imageRules = ['image' => 'mimes:jpeg,jpg,png,gif|required|max:4096'];
        //     foreach($imageFiles as $image) {
        //         $validator = Validator::make(['image' => $image],$imageRules);
        //         if($validator->fails()) {
        //            $reponse_message  .= $validator->errors()->getMessage(); 
        //         }
        //         else {
        //             $path = $image->store(public_path('images'));
        //             $productImage = ProductImages::create([
        //                 'product_id' => $product_id,
        //                 'image_filename' => $path
        //             ]);
        //         }
        //     }
        // }
        
        // if(isset($reponse_message ) === true && $reponse_message  === '') {
        //     $reponse_message = 'Upload Success';
        // }
        // return ['message' => $reponse_message];
        
    }

    // public static function storeImages($imageFiles, Product $product)
    // {
    //     $success = false;
    //     if(!$product) {
    //         return ['success' => $success, 'message' => 'Product not found'];
    //     }
    //     $reponse_message = '';
    //     if($imageFiles) {
    //         $reponse_message = 'Image files exist '.$imageFiles;
    //         return ['success' => $success, 'message' => 'Product not found','imageFiles' => $imageFiles];
    //     }
    //     $imageRules = ['image' => 'mimes:jpeg,jpg,png,gif|required|max:4096'];
    //     foreach($imageFiles as $image) {
    //         $validator = Validator::make(['image' => $image],$imageRules);
    //         if($validator->fails()) {
    //             $reponse_message  .= $validator->errors()->getMessage(); 
    //         }
    //         else {
    //             $path = $image->store(public_path('images'));
    //             $productImage = ProductImages::create([
    //                 'product_id' => $product_id,
    //                 'image_filename' => $path
    //             ]);
    //             $success = true; //at least one file has to upload for a success if we have files
    //         }
    //     }
    //     return ['success' => $success, 'message' => $reponse_message];
    // }
    
    public function destroy(Product $product, ProductImage $productImage)
    {
        $path = public_path('images/'.$productImage->image_filename);
        if(File::Exits($path))
        {
            Storage::delete($path);
        }
        return $productImage->destroy();
    }
}
