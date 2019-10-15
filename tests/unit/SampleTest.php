<?php
use PHPUnit\Framework\TestCase;
use bmarwell\WebtreesModules\MissingTombstones\TombstoneListService;
use Fisharebest\Webtrees\Individual;


class TombstoneListServiceTest extends TestCase {
  public function testHasTombsone() {
      $hasTs = TombstoneListService::personHasTombstone(null);
      $this->assertEquals(false, $hasTs);
  }
}

