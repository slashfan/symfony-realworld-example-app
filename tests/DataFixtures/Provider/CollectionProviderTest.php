<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Provider;

use App\DataFixtures\Provider\CollectionProvider;
use PHPUnit\Framework\TestCase;

/**
 * CollectionProviderTest.
 */
class CollectionProviderTest extends TestCase
{
    public function testCollection(): void
    {
        $this->assertSame([], CollectionProvider::collection([])->toArray());
        $this->assertSame(['a'], CollectionProvider::collection(['a'])->toArray());
        $this->assertSame(['a', 'b'], CollectionProvider::collection(['a', 'b'])->toArray());
    }
}
