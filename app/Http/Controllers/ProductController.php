<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

use App\Http\Resource\Product\ProductCollection;
use App\Http\Resource\Product\ProductResource;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductCollection::collection(Product::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $product = new Product;
        $product->name = $request->name;
        $product->detail = $request->detail;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->discount = $request->discount;
        $product->save();
        return response()->json(["status" => "success", "error" => false, "message" => 'Product created.']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
       return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json(["status" => "success", "error" => false, "message" => 'Product updated.']); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $product_id)
    {
        $request->merge(['product_id' => $product_id]); 

        $validator = Validator::make($request->all(), [
           'product_id'  =>  'exists:products,id',
        ]);

        // If the validation failed then send response with error
        if ($validator->fails()) {
            return response()->json(["status" => "failed", "error" => true, "message" => $validator->errors()->first()]);
        }
        
        $res = Product::destroy($product_id);
        if($res){
            return response()->json(["status" => "success", "error" => false, "message" => 'Product deleted.']);
        }else{
            return response()->json(["status" => "failed", "error" => true, "message" => 'Something went wrong.']);
        }
        
        
    }
}
