<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KraCategory extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Services\Traits\RevisionableCommonTrait;
    protected $table = 'kra_category';
    protected $fillable = ['id', 'name', 'description', 'sort', 'color'];
}
