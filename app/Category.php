<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
    public $table = "categories";

    /**
     * Get the products associated with the category.
     */
    public function products(){
    	return $this->hasMany('App\Product', 'category_id');
    }
}
