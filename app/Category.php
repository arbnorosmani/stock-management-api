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

    /**
     * @param $title
     * @return string
     */
    public static function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title, '-');

        if(!Category::checkIfSlugExists($slug, $id)){
            return $slug;
        }

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = Category::getRelatedSlugs($slug, $id);

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
        return Category::select('slug')->where('slug', 'like', $slug.'%')
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
        $categories = Category::select('slug')->where('slug', '=', $slug)
            ->where('id', '<>', $id)
            ->count();

        if ( $categories == 0 ) return false;

        return true;       
    }
}
