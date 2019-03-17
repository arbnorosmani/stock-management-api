<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;

class SettingsController extends Controller
{
    

	/**
     * Get all settings
     *
     * @return [array] settings
     */
    public function getSettings(){

    	$settings = Setting::all();

    	$data = [];
        foreach($settings as $setting){
            $data[$setting->key] = $setting->value;
        }

        return response()->json(['settings' => $data]);

    }


    /**
     * Get all settings
     *
     * @param [Request] $request
     *
     * @return [array] settings
     */
    public function updateSettings(Request $request){
    	try{
            $input = $request->all();
            foreach($input as $key => $value) {
                $setData = Setting::setSetting($key, $value);
            }

            return response()->json(['success' => true, 'message' => 'settings_updated']);
        }catch( \Exception $e){
            return response()->json(['success' => false, 'message' => 'settings_not_updated']);
        }

        return response()->json(['success' => false, 'message' => 'settings_not_updated']);

    }
}
