<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\Input;
use App\ProductCategory;
use Validator;

class ProductsController extends Controller
{

	/**
     * Get all products
     *
     * @param  [Request] request
     * 
     * @return [array]  products
     */
	public function getProducts(Request $request){

		$total = Product::count();

		$products = Product::with(['brand'])
			->orderBy(Input::get('order', 'id'), Input::get('type', 'DESC'))
            ->paginate(Input::get('size', '10'));

        return response()->json([ 
        	'products' => $products, 
        	'total' => $total 
        ]);    

	}

	/**
     * Get single product
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [object] product
     */
	public function getProduct($id){
		try{

			$product = Product::where('id', $id)
				->with(['brand', 'categories', 'user'])
				->first();

			if(!empty($product)){
				return response()->json([
					'success' => true, 
					'product' => $product
				]);
			}	

		}catch(\Exception $e){
			return response()->json(['success' => false]);
		}

		return response()->json(['success' => false]);
	}


	/**
     * Delete product
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [string] message
     */
	public function destroy($id){
		try{
			$product = Product::find($id);

			if(!empty($product)){
				$product->delete();

				return response()->json([ 
					'success' => true, 
					'message' => 'product_deleted' 
				]);
			}

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'product_not_deleted' 
		]);
	}

	/**
     * Delete multiple products
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function bulkDelete(Request $request){
		try{
			
			$products = Product::whereIn('id', $request->ids)->delete();

			return response()->json([ 
				'success' => true, 
				'message' => 'products_deleted'
			]);
		

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'products_not_deleted' 
		]);
	}

	/**
     * Generate unique slug based on product name
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function generateSlug(Request $request){
		if(! $request->has('name') ){
			return response()->json([ 
				'success' => false, 
				'slug' => '' 
			]);
		}

		$id = 0;
		if( $request->has('id') ){
			$id = $request->id;
		}
		$slug = Product::createSlug($request->name, $id);

		return response()->json([ 
			'success' => true, 
			'slug' => $slug 
		]);

	}

	/**
     * Store product
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function store(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'slug' => 'required|unique:categories',
	            'code' => 'required',
	            'price' => 'required|numeric',
	            'quantity' => 'required|numeric',
	            'brand' => 'required|numeric'
	        ]);

			if ($validator->fails()) {
	            return response()->json([
	            	'success' => false, 
	            	'errors' => $validator->errors() 
	            ]);
	        }

			$product = new Product;
			$product->fill($request->only('name', 'slug', 'code', 'price', 'quantity'));
			$product->slug = $request->slug;
			$product->brand_id = $request->brand;
			$product->created_by = $request->user()->id;
			$product->save();

			// Save product-category relations
			foreach ($request->categories as $category) {
				$addRelation = ProductCategory::newConnection($product->id, $category['id']);
			}

			return response()->json([ 
				'success' => true, 
				'message' => 'product_stored' 
			]);

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'product_not_stored' 
		]);
	}

	/**
     * Update product
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function update(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'slug' => 'required|unique:categories,slug,'.$request->id,
	            'code' => 'required',
	            'price' => 'required|numeric',
	            'quantity' => 'required|numeric',
	            'brand' => 'required|numeric'
	        ]);

			// Check if request data is valid
			if ($validator->fails()) {
	            return response()->json([
	            	'success' => false, 
	            	'errors' => $validator->errors() 
	            ]);
	        }

			$input = $request->all();

			$product = Product::find($request->id);
			$product->fill($request->only('name', 'slug', 'code', 'price', 'quantity'));
			$product->slug = $request->slug;
			$product->brand_id = $request->brand;
			$product->created_by = $request->user()->id;
			$product->save();

			$oldRelations = \DB::table('product_categories')->where('product_id', $request->id)->delete();
			// Save product-category relations
			foreach ($request->categories as $category) {
				$addRelation = ProductCategory::newConnection($product->id, $category['id']);
			}

			return response()->json([ 
				'success' => true, 
				'message' => 'category_updated' 
			]);

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'category_not_updated' 
		]);
	}


}
