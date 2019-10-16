<?php

use bmarwell\WebtreesModules\MissingTombstones\TombstoneListService;
use PHPUnit\Framework\TestCase;


class TombstoneListServiceTest extends TestCase {
  public function testHasTombsone() {
      $hasTs = TombstoneListService::personHasTombstone(null);
      $this->assertEquals(false, $hasTs);
  }
}

