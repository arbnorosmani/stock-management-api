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
}
