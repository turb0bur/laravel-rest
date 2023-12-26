<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    public string $transformer = BuyerTransformer::class;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
