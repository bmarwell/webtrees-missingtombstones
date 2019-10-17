<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use Fisharebest\Webtrees\Functions\FunctionsImport as F;
use Illuminate\Database\Capsule\Manager as DB;

class TombstoneListServiceTest extends AbstractDBTestCase
{

    /** @var TombstoneListService $service */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new TombstoneListService($this->getLocalizationService(), $this->getTree());
    }

    public function tearDown() : void
    {
        parent::tearDown();
    }

    public function testServiceCanReadPersons(): void
    {
        /** @var Individual[] $individuals */
        $individuals = $this->service->individualsWithTombstone(1);
        $query_log = DB::getQueryLog();
        $last_query = array_pop($query_log);

        $this->assertEmpty($individuals);
        $this->assertTrue(strpos($last_query['query'], 'select * from "individuals"') > -1, "should contain query from individual");
        $this->assertTrue(strpos($last_query['query'], 'where "d_fact"') > -1);
        $this->assertContains('DEAT', $last_query['bindings']);
    }

    public function testServiceCanReadPersons_nonEmpty(): void
    {

        $gedrec = <<<EOT
0 @I499@ INDI
1 NAME Jane /Doe/
2 GIVN Jane
2 SURN Doe
2 _MARNM Jane /North/
1 SEX F
1 BIRT
2 DATE 18 NOV 1888
1 DEAT
2 DATE 16 APR 1976
1 FAMS @F187@
1 FAMC @F189@
1 CHAN
2 DATE 22 FEB 2015
3 TIME 16:31:48
1 OBJE @M170@
EOT;
        F::importRecord($gedrec, $this->getTree(), $update = true);

        /** @var Query $last_query */
        $last_query = $this->getLastQuery();
        print_r($last_query);

        /** @var Individual[] $individuals */
        $individuals = $this->service->individualsWithTombstone(1);
        $query_log = DB::getQueryLog();
        $last_query = array_pop($query_log);

        print_r($last_query);

        $this->assertNotEmpty($individuals);
    }
}
