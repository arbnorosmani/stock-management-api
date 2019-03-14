<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
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
    protected $fillable = ['code', 'name', 'slug'];


    /**
     * The table associated with the model.
     *
     * @var string $table
     */
    public $table = "brands";


    /**
     * Get the products associated with the brand.
     */
    public function products(){
    	return $this->hasMany('App\Product', 'brand_id');
    }


}
