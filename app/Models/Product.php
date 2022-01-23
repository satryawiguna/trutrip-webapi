<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected static function boot() {
        parent::boot();

        static::deleting(function($product) {
            if ($product->medias()->count() > 0)
                $product->medias()->delete();
        });
    }

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'destination',
        'schedule_start',
        'schedule_end',
        'description',
        'photo'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medias()
    {
        return $this->hasMany(Media::class, 'product_id');
    }
}
