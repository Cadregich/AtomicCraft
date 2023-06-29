<?php

namespace App\Models;

use App\Http\Requests\CreateRequest;
use App\Http\Requests\GoodsSearchRequest;
use App\Services\Products\StoreProducts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'goods';
    protected $guarded = [];
    private $service;

    public function __construct(array $attributes = [])
    {
        $this->service = new StoreProducts();
        parent::__construct($attributes);
    }


    public function associations()
    {
        return $this->belongsToMany(Association::class,
            'goods_associations', 'goods_id', 'associations_id');
    }

    public function mod()
    {
        return $this->belongsTo(Mod::class, 'mod_id', 'id');
    }
}
