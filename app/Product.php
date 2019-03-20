<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
    protected $fillable = ['created_by', 'brand_id', 'category_id', 'code', 'name', 'image', 'price', 'quantity'];


    /**
     * The table associated with the model.
     *
     * @var string $table
     */
    public $table = "products";

    /**
     * Get the user record associated with the product.
     */
    public function user(){
    	return $this->belongsTo('App\User', 'created_by');
    }

	/**
     * Get the brand record associated with the product.
     */
    public function brand(){
    	return $this->belongsTo('App\Brand', 'brand_id');
    }

    /**
     * Get the categories associated with the product.
     */
    public function categories(){
    	return $this->belongsToMany('App\Category', 'product_categories', 'product_id');
    }
}
