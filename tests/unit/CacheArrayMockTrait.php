<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use AspectMock\Test as test;
use Illuminate\Cache\NullStore;
use Illuminate\Cache\Repository;

trait CacheArrayMockTrait
{
    protected function initCacheArray(): void
    {
        $namespaces = ['', '\\', 'bmhm\WebtreesModules\MissingTombstones', '\Fisharebest\Webtrees'];

        foreach ($namespaces as $key => $namespace) {
            test::func($namespace, 'app', function ($in) {
              if ($in === 'cache.array') {
                return new Repository(new NullStore());
              }

              return null;
            });
        }

    }
}
