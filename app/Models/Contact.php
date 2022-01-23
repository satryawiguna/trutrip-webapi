<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contacts';

    public $incrementing = false;

    protected $keyType = 'string';

    public static function boot(){
        parent::boot();

        static::creating(function ($contact) {
            $contact->id = Str::uuid(36);
        });
    }

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'full_name',
        'nick_name',
        'post_code',
        'country',
        'state',
        'city',
        'address',
        'mobile',
        'photo'
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
