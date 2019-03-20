<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\Input;

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

		$products = Product::with(['brand', 'categories'])
			->orderBy(Input::get('order', 'id'), Input::get('type', 'DESC'))
            ->paginate(Input::get('size', '10'));

        return response()->json([ 'products' => $products, 'total' => $total ]);    
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

				return response()->json([ 'success' => true, 'message' => 'product_deleted' ]);
			}

		}catch(\Exception $e){
			return response()->json([ 'success' => false, 'message' => $e->getMessage() ]);
		}

		return response()->json([ 'success' => false, 'message' => 'product_not_deleted' ]);
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

			return response()->json([ 'success' => true, 'message' => 'products_deleted' ]);
		

		}catch(\Exception $e){
			return response()->json([ 'success' => false, 'message' => $e->getMessage() ]);
		}

		return response()->json([ 'success' => false, 'message' => 'products_not_deleted' ]);
	}

}
