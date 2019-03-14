<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    

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

		return response()->json(['success' => true, 'message' => 'profile_updated']);
	}

}
