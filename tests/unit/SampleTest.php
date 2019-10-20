<?php

use bmhm\WebtreesModules\MissingTombstones\TombstoneListService;
use PHPUnit\Framework\TestCase;


class TombstoneListServiceTest extends TestCase {

    public function testHasTombsone(): void
    {
      $hasTs = TombstoneListService::personHasTombstone(null);
      $this->assertEquals(false, $hasTs);
  }
}

