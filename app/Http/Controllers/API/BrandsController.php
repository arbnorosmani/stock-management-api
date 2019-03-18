<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Brand;
use Validator;
use Illuminate\Support\Facades\Input;

class BrandsController extends Controller
{


	/**
     * Get all brands
     *
     * @param  [Request] request
     * 
     * @return [array]  brands
     */
	public function getBrands(Request $request){

		$total = Brand::count();

		$brands = Brand::orderBy(Input::get('order', 'id'), Input::get('type', 'DESC'))
            ->paginate(Input::get('size', '10'));

        return response()->json([ 'brands' => $brands, 'total' => $total ]);    
	}

	/**
     * Get single brand
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [object] brand
     */
	public function getBrand($id){
		try{

			$brand = Brand::find($id);

			if(!empty($brand))
				return response()->json(['success' => true, 'brand' => $brand]);
			

		}catch(\Exception $e){
			return response()->json(['success' => false]);
		}

		return response()->json(['success' => false]);
	}

	/**
     * Delete brand
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [string] message
     */
	public function destroy($id){
		try{
			$brand = Brand::find($id);

			if(!empty($brand)){
				$brand->delete();

				return response()->json([ 'success' => true, 'message' => 'brand_deleted' ]);
			}

		}catch(\Exception $e){
			return response()->json([ 'success' => false, 'message' => $e->getMessage() ]);
		}

		return response()->json([ 'success' => false, 'message' => 'brand_not_deleted' ]);
	}

	/**
     * Delete multiple brands
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function bulkDelete(Request $request){
		try{
			
			$brands = Brand::whereIn('id', $request->ids)->delete();

			return response()->json([ 'success' => true, 'message' => 'brands_deleted' ]);
		

		}catch(\Exception $e){
			return response()->json([ 'success' => false, 'message' => $e->getMessage() ]);
		}

		return response()->json([ 'success' => false, 'message' => 'brands_not_deleted' ]);
	}


	/**
     * Store caetgory
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function store(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'slug' => 'required|unique:brands',
	            'code' => 'required'
	        ]);

			if ($validator->fails()) {
	            return response()->json(['success' => false, 'errors' => $validator->errors() ]);
	        }

			$input = $request->all();

			$brand = new brand;
			$brand->fill($input);
			$brand->save();

			return response()->json([ 'success' => true, 'message' => 'brand_stored' ]);
		}catch(\Exception $e){
			return response()->json([ 'success' => false, 'message' => $e->getMessage() ]);
		}

		return response()->json([ 'success' => false, 'message' => 'brand_not_stored' ]);
	}

	/**
     * Update brand
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function update(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'slug' => 'required|unique:brands,slug,'.$request->id,
	            'code' => 'required'
	        ]);

			// Check if request data is valid
			if ($validator->fails()) {
	            return response()->json(['success' => false, 'errors' => $validator->errors() ]);
	        }

			$input = $request->all();

			$brand = Brand::find($request->id);
			$brand->fill($input);
			$brand->save();

			return response()->json([ 'success' => true, 'message' => 'brand_updated' ]);
		}catch(\Exception $e){
			return response()->json([ 'success' => false, 'message' => $e->getMessage() ]);
		}

		return response()->json([ 'success' => false, 'message' => 'brand_not_updated' ]);
	}

	/**
     * Generate unique slug based on brand name
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function generateSlug(Request $request){
		if(! $request->has('name') ){
			return response()->json([ 'success' => false, 'slug' => '' ]);
		}

		$id = 0;
		if( $request->has('id') ){
			$id = $request->id;
		}
		$slug = Brand::createSlug($request->name, $id);

		return response()->json([ 'success' => true, 'slug' => $slug ]);
	}
}
