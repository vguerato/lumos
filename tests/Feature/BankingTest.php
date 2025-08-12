<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BankingTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(User $user): array
    {
        $token = $user->createToken('api')->plainTextToken;
        return ['Authorization' => 'Bearer '.$token];
    }

    public function test_impede_saque_com_saldo_insuficiente(): void
    {
        $user = User::factory()->create();
        $acc = Account::create(['user_id' => $user->id, 'name' => 'Main', 'balance' => '10.00']);

        $payload = [
            'account_id' => $acc->id,
            'amount' => 20,
            'type' => 'withdrawal',
        ];

        $res = $this
            ->withHeaders($this->authHeaders($user))
            ->postJson('/api/transact', $payload);

        $res->assertStatus(500);
    }

    public function test_usuario_so_acessa_suas_contas(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $accA = Account::create(['user_id' => $userA->id, 'name' => 'A', 'balance' => '0.00']);

        // autentica como userB para tentar acessar conta de userA
        $res = $this
            ->withHeaders($this->authHeaders($userB))
            ->getJson("/api/accounts/{$accA->id}");

        $res->assertStatus(500);
    }

    public function test_transferencia_funciona_corretamente(): void
    {
        $user = User::factory()->create();

        $from = Account::create(['user_id' => $user->id, 'name' => 'From', 'balance' => '100.00']);
        $to = Account::create(['user_id' => $user->id, 'name' => 'To', 'balance' => '0.00']);

        $res = $this
            ->withHeaders($this->authHeaders($user))
            ->postJson('/api/transfer', [
                'account_from' => $from->id,
                'account_to' => $to->id,
                'amount' => 25,
            ]);

        $res->assertStatus(201);

        $from->refresh();
        $to->refresh();

        $this->assertEquals(75.0, $from->balance);
        $this->assertEquals(25.0, $to->balance);
    }
}
