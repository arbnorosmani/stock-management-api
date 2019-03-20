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
    protected $fillable = ['created_by', 'brand_id', 'code', 'name', 'image', 'price', 'quantity'];


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


    /**
     * @param $title
     * @return string
     */
    public static function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title, '-');

        if(!Product::checkIfSlugExists($slug, $id)){
            return $slug;
        }

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = Product::getRelatedSlugs($slug, $id);

        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        return $slug;
    }


    /**
     * @param string $slug
     * @param int $id
     * @return Illuminate\Support\Collection
     */
    protected static function getRelatedSlugs($slug, $id)
    {
        return Product::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * @param string $slug
     *
     * @return boolean
     */
    protected static function checkIfSlugExists($slug, $id)
    {
        $products = Product::select('slug')->where('slug', '=', $slug)
            ->where('id', '<>', $id)
            ->count();

        if ( $products == 0 ) return false;

        return true;       
    }
}
