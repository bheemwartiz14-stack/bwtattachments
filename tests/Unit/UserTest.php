<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;

test('user can be created', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create(['company_id' => $company->id]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->not->toBeEmpty();
    expect($user->email)->not->toBeEmpty();
});

test('user has uuid', function () {
    $user = User::factory()->create();

    expect($user->id)->toBeString();
    expect(Str::isUuid($user->id))->toBeTrue();
});

test('user belongs to company', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create(['company_id' => $company->id]);

    expect($user->company)->toBeInstanceOf(Company::class);
    expect($user->company->id)->toEqual($company->id);
});

test('user has quotations relationship', function () {
    $user = User::factory()->create();

    expect($user->quotations)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class);
});

test('user status is boolean', function () {
    $user = User::factory()->create();

    expect($user->status)->toBeBool();
});
