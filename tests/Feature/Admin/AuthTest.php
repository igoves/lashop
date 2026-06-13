<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest hitting GET /admin redirects to login', function () {
    $this->get('/admin')
        ->assertRedirect('/login');
});

test('authenticated non-admin hitting GET /admin gets 403', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('authenticated admin hitting GET /admin gets 200', function () {
    $user = User::factory()->create(['is_admin' => true]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('POST /login with valid credentials redirects', function () {
    $user = User::factory()->create([
        'is_admin' => true,
        'password' => bcrypt('password'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect();
});

test('POST /login with invalid credentials returns back with errors', function () {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('correct-password'),
    ]);

    $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');
});

test('POST /logout redirects and invalidates session', function () {
    $user = User::factory()->create(['is_admin' => true]);

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    $this->assertGuest();
});
