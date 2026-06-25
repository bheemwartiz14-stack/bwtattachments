<?php

use App\Models\User;
use Illuminate\Support\Str;

test('user has uuid', function () {
    $user = User::factory()->create();

    expect($user->id)->toBeString();
    expect(Str::isUuid($user->id))->toBeTrue();
});

test('user has quotations relationship', function () {
    $user = User::factory()->create();

    expect($user->quotations)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class);
});

test('user status is boolean', function () {
    $user = User::factory()->create();

    expect($user->status)->toBeBool();
});
