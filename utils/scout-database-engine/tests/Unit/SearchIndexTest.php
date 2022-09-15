<?php

namespace Lunar\ScoutDatabaseEngine\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\ScoutDatabaseEngine\SearchIndex;
use Lunar\ScoutDatabaseEngine\Tests\TestCase;

class SearchIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_model()
    {
        $searchIndex = new SearchIndex();
        $searchIndex->key = 1;
        $searchIndex->index = 'posts';
        $searchIndex->field = 'title';
        $searchIndex->content = 'Test 1 2 3';
        $searchIndex->save();

        $this->assertInstanceOf(SearchIndex::class, $searchIndex);
    }
}
