<?php

namespace Tests\Feature\Http\Controllers\Buyer;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BuyerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        Seller::factory()
            ->has(Product::factory(), 'products')
            ->create();
    }

    #[Test]
    public function buyersListCanBeRetrieved(): void
    {
        $this->actingAsAdmin();

        $buyersCount = 3;

        Buyer::factory()
            ->has(Transaction::factory(), 'transactions')
            ->count($buyersCount)
            ->create();

        $response = $this->getJson(
            route('buyers.index')
        );

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonCount($buyersCount, 'data');
    }

    #[Test]
    public function buyerCanBeShown(): void
    {
        $buyer = $this->actingAsBuyerAndAdmin();

        $response = $this->getJson(
            route('buyers.show', ['buyer' => $buyer->id])
        );

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonFragment(['id' => $buyer->id]);
    }

    #[Test]
    public function nonExistentBuyerCannotBeShown(): void
    {
        $this->actingAsBuyerAndAdmin();

        $response = $this->getJson(
            route('buyers.show', ['buyer' => 999])
        );

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    #[Test]
    public function buyerWithoutTransactionsIsNotListed(): void
    {
        $this->actingAsAdmin();

        $buyer = Buyer::factory()
            ->create();

        $response = $this->getJson(
            route('buyers.index')
        );

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonMissing(['id' => $buyer->id]);
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()
            ->create([
                'is_admin' => User::ADMIN_USER
            ]);

        Passport::actingAs($user, ['read-general']);

        return $user;
    }

    protected function actingAsBuyerAndAdmin(): Buyer
    {
        $buyer = Buyer::factory()
            ->has(Transaction::factory(), 'transactions')
            ->create([
                'is_admin' => User::ADMIN_USER
            ]);

        $this->actingAs($buyer, 'api');

        return $buyer;
    }
}
