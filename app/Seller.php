<?php

namespace App;

use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends User
{
    public string $transformer = SellerTransformer::class;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new SellerScope());
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
