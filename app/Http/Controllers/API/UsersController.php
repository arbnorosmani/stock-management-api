<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

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
				return response()->json(['success' => true, 'user' => $user]);
			

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

		return response()->json(['success' => true, 'message' => 'profile_updated']);
	}



}
