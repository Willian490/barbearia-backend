<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('responde o status da API no envelope padrão', function () {
    $this->getJson('/api/v1/status')
        ->assertOk()
        ->assertJsonPath('data.versao', 'v1');
});

it('bloqueia rota autenticada sem token', function () {
    $this->getJson('/api/v1/user')->assertUnauthorized();
});

it('responde 401 em JSON mesmo sem o header Accept', function () {
    $this->get('/api/v1/user')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Unauthenticated.');
});

it('libera rota autenticada com token do Sanctum', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->getJson('/api/v1/user')->assertOk();
});
