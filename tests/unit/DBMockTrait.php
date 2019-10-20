<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use Fisharebest\Webtrees\Services\MigrationService;
use Fisharebest\Webtrees\Webtrees;
use Illuminate\Database\Capsule\Manager as DB;
use RuntimeException;

trait DBMockTrait
{
    private $db;

    protected function initDB()
    {
        $this->db = new DB();
        $this->db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'schema' => $this->getName(),
        ]);
        $this->db->setAsGlobal();

        $this->db->getConnection()->enableQueryLog();
        $migration_service = new MigrationService();
        $migration_service->updateSchema('\Fisharebest\Webtrees\Schema', 'WT_SCHEMA_VERSION', Webtrees::SCHEMA_VERSION);
        if ($this->getLastQuery() === null) {
            throw new RuntimeException("Schema upgrade did not run.");
        }
        // Create config data
        $migration_service->seedDatabase();
    }

    protected function getDb(): DB
    {
        return $this->db;
    }

    protected function getLastQuery(): ?array
    {
        $query_log = $this->db->getConnection()->getQueryLog();
        return array_pop($query_log);
    }


}
