<?php

uses(\Lunar\Tests\Admin\Unit\Models\TestCase::class)
    ->group('lunar.admin.models');

test('can get full name', function () {
    $staff = \Lunar\Admin\Models\Staff::factory()->create([
        'first_name' => 'Joe',
        'last_name' => 'Bloggs',
    ]);

    expect($staff->full_name)->toBe('Joe Bloggs');
});

test('can search staff by name', function () {
    \Lunar\Admin\Models\Staff::factory()->create([
        'first_name' => 'Joe',
        'last_name' => 'Bloggs',
    ]);

    \Lunar\Admin\Models\Staff::factory()->create([
        'first_name' => 'Tim',
        'last_name' => 'Bloggs',
    ]);

    \Lunar\Admin\Models\Staff::factory()->create([
        'first_name' => 'Bill',
        'last_name' => 'Chance',
    ]);

    expect(\Lunar\Admin\Models\Staff::search('Bloggs')->get())->toHaveCount(2)
        ->and(\Lunar\Admin\Models\Staff::search('Bill')->get())->toHaveCount(1)
        ->and(\Lunar\Admin\Models\Staff::search('Joe Bloggs')->get())->toHaveCount(1);
});
