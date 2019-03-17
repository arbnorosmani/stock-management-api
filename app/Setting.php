<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The primary key of the table.
     *
     * @var string $primaryKey
     */
    public $primaryKey = "id";


    /**
     * Fields that can be filled in CRUD.
     *
     * @var array $fillable
     */
    protected $fillable = ['key', 'value'];


    /**
     * The table associated with the model.
     *
     * @var string $table
     */
    public $table = "settings";


    /**
     * Add or update an item in settings.
     *
     * @param  $key
     * @param  $value
     * @return object
     */
    public static function setSetting($key, $value)
    {
        $result = Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        return $result;
    }

    /**
     * Get a setting
     *
     * @param  $key
     * @throws \Exception
     */
    public static function getSetting($key)
    {
        $setting = Setting::where('key', $key)->first();
        if($setting) {
            return $setting->value;
        }
        return;
    }
}
