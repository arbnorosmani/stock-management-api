<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\User;
use Validator;

class UsersController extends Controller
{


	/**
     * Get all users
     *
     * @param  [Request] request
     * 
     * @return [array] users
     */
	public function getUsers(Request $request){

		$total = User::count();

		$users = User::select("id", "email", "name", "photo_url")
            ->orderBy(Input::get('order', 'id'), Input::get('type', 'DESC'))
            ->paginate(Input::get('size', '10'));

        return response()->json([ 
        	'users' => $users, 
        	'total' => $total 
        ]);

	}

	/**
     * Get single user
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [object] user
     */
	public function getUser($id){
		try{

			$user = User::find($id);

			if(!empty($user))
				return response()->json([
					'success' => true, 
					'user' => $user
				]);
			

		}catch(\Exception $e){
			return response()->json(['success' => false]);
		}

		return response()->json(['success' => false]);
	}
    

	/**
     * Update data of authenticated user
     *
     * @param  [Request] request
     * @return [boolean] success
     * @return [string] messsage
     */
	public function updateProfile(Request $request){
		$user = $request->user();

		$input = $request->all();
		$user->fill($input);
		$user->save();

		return response()->json([
			'success' => true, 
			'message' => 'profile_updated'
		]);

	}

	/**
     * Delete user
     *
     * @param  [integer] id
     * @return [boolean] success
     * @return [string] message
     */
	public function destroy($id){
		try{
			$user = User::find($id);

			if(!empty($user)){
				$user->delete();

				return response()->json([ 
					'success' => true, 
					'message' => 'user_deleted' 
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
			'message' => 'user_not_deleted' 
		]);

	}

	/**
     * Delete multiple users
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function bulkDelete(Request $request){
		try{
			
			$users = User::whereIn('id', $request->ids)->delete();

			return response()->json([ 
				'success' => true, 
				'message' => 'users_deleted' 
			]);
		

		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'users_not_deleted' 
		]);
	}


	/**
     * Store user
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function store(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'email' => 'required|unique:users',
	            'password' => 'required|confirmed'
	        ]);

			if ($validator->fails()) {
	            return response()->json([
	            	'success' => false, 
	            	'errors' => $validator->errors() 
	            ]);
	        }

			$input = $request->all();

			$user = new User;
			$user->fill($input);
			$user->save();

			return response()->json([ 
				'success' => true, 
				'message' => 'users_stored' 
			]);
		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'users_not_stored' 
		]);

	}

	/**
     * Update user
     *
     * @return [boolean] success
     * @return [string] message
     */
	public function update(Request $request){
		try{
			$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'email' => 'required|unique:users,email,'.$request->id,
	        ]);

			// Check if request data is valid
			if ($validator->fails()) {
	            return response()->json([
	            	'success' => false, 
	            	'errors' => $validator->errors() 
	            ]);
	        }

			$input = $request->all();

			$user = User::find($request->id);
			$user->fill($input);
			$user->save();

			return response()->json([ 
				'success' => true, 
				'message' => 'users_updated' 
			]);
		}catch(\Exception $e){
			return response()->json([ 
				'success' => false, 
				'message' => $e->getMessage() 
			]);
		}

		return response()->json([ 
			'success' => false, 
			'message' => 'users_not_updated' 
		]);

	}

}
