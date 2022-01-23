<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medias';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'product_id',
        'name',
        'mime_type',
        'extension',
        'size',
        'path',
        'url'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
