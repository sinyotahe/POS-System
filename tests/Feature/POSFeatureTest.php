<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class POSFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected Branch $branch;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::factory()->create();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'branch_id' => $this->branch->id,
        ]);
    }

    protected function createProductWithStock(int $stock, array $overrides = []): Product
    {
        $product = Product::factory()->create($overrides);

        DB::table('branch_product')->insert([
            'branch_id' => $this->branch->id,
            'product_id' => $product->id,
            'stock' => $stock,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $product;
    }

    public function test_admin_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_kasir_can_access_pos(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($user)->get(route('pos.index'));

        $response->assertOk();
    }

    public function test_owner_cannot_access_pos(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)->get(route('pos.index'));

        $response->assertForbidden();
    }

    public function test_kasir_cannot_access_products(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($user)->get(route('products.index'));

        $response->assertForbidden();
    }

    public function test_can_create_product(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('products.store'), [
            'category_id' => $category->id,
            'name' => 'Produk Test',
            'sku' => 'TST-001',
            'cost_price' => 5000,
            'sell_price' => 10000,
            'minimum_stock' => 2,
            'status' => true,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['sku' => 'TST-001']);
    }

    public function test_can_process_pos_transaction(): void
    {
        $user = $this->admin;
        $product = $this->createProductWithStock(10, ['sell_price' => 10000]);

        $response = $this->actingAs($user)->post(route('pos.store'), [
            'items' => [
                ['product_id' => $product->id, 'qty' => 2],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 50000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sales', ['grand_total' => 22200]);

        $branchStock = DB::table('branch_product')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $product->id)
            ->value('stock');
        $this->assertEquals(8, $branchStock);
    }

    public function test_can_create_stock_in(): void
    {
        $user = $this->admin;
        $product = $this->createProductWithStock(5, ['cost_price' => 3000]);
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($user)->post(route('stock-ins.store'), [
            'supplier_id' => $supplier->id,
            'items' => [
                ['product_id' => $product->id, 'qty' => 10, 'cost_price' => 3000],
            ],
        ]);

        $response->assertRedirect();

        $branchStock = DB::table('branch_product')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $product->id)
            ->value('stock');
        $this->assertEquals(15, $branchStock);
    }

    public function test_can_create_stock_out(): void
    {
        $user = $this->admin;
        $product = $this->createProductWithStock(10);

        $response = $this->actingAs($user)->post(route('stock-outs.store'), [
            'type' => 'rusak',
            'items' => [
                ['product_id' => $product->id, 'qty' => 3],
            ],
        ]);

        $response->assertRedirect();

        $branchStock = DB::table('branch_product')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $product->id)
            ->value('stock');
        $this->assertEquals(7, $branchStock);
    }

    public function test_admin_can_void_sale(): void
    {
        $user = $this->admin;
        $product = $this->createProductWithStock(5, ['sell_price' => 10000]);
        $sale = Sale::factory()->create(['cashier_id' => $user->id, 'branch_id' => $this->branch->id]);
        $sale->items()->create([
            'product_id' => $product->id,
            'qty' => 2,
            'price' => 10000,
            'subtotal' => 20000,
        ]);

        $response = $this->actingAs($user)->post(route('sales.void', $sale));

        $response->assertRedirect();
        $this->assertNotNull($sale->fresh()->voided_at);

        $branchStock = DB::table('branch_product')
            ->where('branch_id', $this->branch->id)
            ->where('product_id', $product->id)
            ->value('stock');
        $this->assertEquals(7, $branchStock);
    }
}
