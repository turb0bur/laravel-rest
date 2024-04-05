<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Database\Factories\BuyerFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    public string $transformer = BuyerTransformer::class;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    protected static function newFactory(): BuyerFactory
    {
        return BuyerFactory::new();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
