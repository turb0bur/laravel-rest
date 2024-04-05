<?php

namespace App\Models;

use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;
use Database\Factories\SellerFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends User
{
    public string $transformer = SellerTransformer::class;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new SellerScope());
    }


    protected static function newFactory(): SellerFactory
    {
        return SellerFactory::new();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
