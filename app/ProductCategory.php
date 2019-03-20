<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Category;

class ProductCategory extends Model
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
    protected $fillable = ['product_id', 'category_id'];


    /**
     * The table associated with the model.
     *
     * @var string $table
     */
    public $table = "product_categories";
    

    /**
     * Add new record to database
     *
     * @param [ineteger] $productID
     * @param [ineteger] $categoryID
	 *
	 * @return object or null
     */
    public static function newConnection($productID, $categoryID){
    	$product = Product::find($productID);
    	$category = Category::find($categoryID);

    	if( !empty($product) && !empty($category)){
    		$connection = new ProductCategory;
	    	$connection->product_id = $productID;
	    	$connection->category_id = $categoryID;
	    	$connection->save();

	    	return $connection;
    	}
    	
    	return null;
    }
}
