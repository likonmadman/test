<?php

namespace Tests\Feature;

use App\Enums\ProxyStatus;
use App\Models\Proxy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProxyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_and_list_proxy(): void
    {
        $this->postJson('/api/proxies', [
            'host' => '1.2.3.4',
            'port' => 8080,
            'protocol' => 'http',
        ])->assertCreated();

        $this->assertDatabaseHas('proxies', ['host' => '1.2.3.4', 'port' => 8080]);
        $this->getJson('/api/proxies')->assertOk()->assertJsonFragment(['host' => '1.2.3.4']);
    }

    public function test_cannot_create_duplicate_proxy(): void
    {
        $proxy = Proxy::factory()->create();

        $this->postJson('/api/proxies', [
            'host' => $proxy->host,
            'port' => $proxy->port,
            'protocol' => $proxy->protocol->value,
        ])->assertUnprocessable()->assertJsonValidationErrors('host');
    }

    public function test_update_resets_status(): void
    {
        $proxy = Proxy::factory()->create(['status' => ProxyStatus::Online, 'latency_ms' => 120]);

        $this->putJson("/api/proxies/{$proxy->id}", [
            'host' => '5.6.7.8',
            'port' => 1080,
            'protocol' => 'socks5',
        ])->assertOk()->assertJsonPath('data.status', ProxyStatus::Unknown->value);

        $this->assertNull($proxy->fresh()->latency_ms);
    }

    public function test_empty_password_on_update_keeps_old_one(): void
    {
        $proxy = Proxy::factory()->create(['username' => 'user', 'password' => 'secret']);

        $this->putJson("/api/proxies/{$proxy->id}", [
            'host' => $proxy->host,
            'port' => $proxy->port,
            'protocol' => $proxy->protocol->value,
            'username' => 'user',
            'password' => '',
        ])->assertOk();

        $this->assertSame('secret', $proxy->fresh()->password);
    }

    public function test_can_delete_proxy(): void
    {
        $proxy = Proxy::factory()->create();

        $this->deleteJson("/api/proxies/{$proxy->id}")->assertNoContent();
        $this->assertDatabaseMissing('proxies', ['id' => $proxy->id]);
    }

    public function test_check_marks_proxy_online(): void
    {
        Http::fake(['*' => Http::response('')]);

        $proxy = Proxy::factory()->create();

        $this->postJson("/api/proxies/{$proxy->id}/check")->assertOk()->assertJsonPath('data.status', ProxyStatus::Online->value);
        $this->assertDatabaseHas('proxies', ['id' => $proxy->id, 'status' => ProxyStatus::Online->value]);
    }

    public function test_check_marks_proxy_offline_when_connection_fails(): void
    {
        Http::fake(['*' => Http::failedConnection()]);

        $proxy = Proxy::factory()->create();

        $this->postJson("/api/proxies/{$proxy->id}/check")
            ->assertOk()
            ->assertJsonPath('data.status', ProxyStatus::Offline->value)
            ->assertJsonPath('data.latency_ms', null);
    }

    public function test_check_marks_proxy_offline_on_proxy_error(): void
    {
        Http::fake(['*' => Http::response('', 407)]);

        $proxy = Proxy::factory()->create();

        $this->postJson("/api/proxies/{$proxy->id}/check")
            ->assertOk()
            ->assertJsonPath('data.status', ProxyStatus::Offline->value);
    }

    public function test_check_all_updates_every_proxy(): void
    {
        Http::fake(['*' => Http::response('')]);

        Proxy::factory()->count(3)->create();

        $this->postJson('/api/proxies/check-all')->assertOk();

        $this->assertSame(3, Proxy::where('status', ProxyStatus::Online)->count());
    }
}
