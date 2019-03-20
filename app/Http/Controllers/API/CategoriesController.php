<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Validator;
use Illuminate\Support\Facades\Input;

class CategoriesController extends Controller
{


	/**
     * Get all categories
     *
     * @param  [Request] request
     * 
     * @return [array]  categories
     */
	public function getCategories(Request $request){

		$total = Category::count();

		$categories = Category::orderBy(Input::get('order', 'id'), Input::get('type', 'DESC'))
            ->paginate(Input::get('size', '10'));

        return response()->json([ 
        	'categories' => $categories, 
        	'total' => $total 
        ]);    
	}

	/**
     * Get single category
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [object] category
     */
	public function getCategory($id){
		try{

			$category = Category::find($id);

			if(!empty($category)){
				return response()->json([
					'success' => true, 
					'category' => $category
				]);
			}	

		}catch(\Exception $e){
			return response()->json(['success' => false]);
		}

		return response()->json(['success' => false]);
	}

	/**
     * Delete category
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [string] message
     */
	public function destroy($id){
		try{
			$category = Category::find($id);

			if(!empty($category)){
				$category->delete();

				return response()->json([ 
					'success' => true, 
					'message' => 'category_deleted' 
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
			'message' => 'category_not_deleted' 
		]);
	}

	/**
     * Delete multiple categories
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function bulkDelete(Request $request){
		try{
			
			$categories = Category::whereIn('id', $request->ids)->delete();

			return response()->json([ 
				'success' => true, 
				'message' => 'categories_deleted' 
			]);
		

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'categories_not_deleted' 
		]);
	}


	/**
     * Store category
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function store(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'slug' => 'required|unique:categories',
	            'code' => 'required'
	        ]);

			if ($validator->fails()) {
	            return response()->json([
	            	'success' => false, 
	            	'errors' => $validator->errors() 
	            ]);
	        }

			$input = $request->all();

			$category = new Category;
			$category->fill($input);
			$category->save();

			return response()->json([ 
				'success' => true, 
				'message' => 'category_stored' 
			]);

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'category_not_stored' 
		]);
	}

	/**
     * Update category
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function update(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'slug' => 'required|unique:categories,slug,'.$request->id,
	            'code' => 'required'
	        ]);

			// Check if request data is valid
			if ($validator->fails()) {
	            return response()->json([
	            	'success' => false, 
	            	'errors' => $validator->errors() 
	            ]);
	        }

			$input = $request->all();

			$category = Category::find($request->id);
			$category->fill($input);
			$category->save();

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

	/**
     * Generate unique slug based on category name
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
		$slug = Category::createSlug($request->name, $id);

		return response()->json([ 
			'success' => true, 
			'slug' => $slug 
		]);
	}

	/**
     * Search catgories by value
     *
     * @param  [Request] request
     * 
     * @return [array]  categories
     */
	public function search(Request $request, $value){

		$categories = Category::where('name', 'LIKE', '%'.$value.'%')
			->orderBy('name', 'asc')
			->get();

        return response()->json([ 
        	'categories' => $categories, 
        ]);    

	}

}
