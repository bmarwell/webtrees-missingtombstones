<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use AspectMock\Test as test;
use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\Services\LocalizationService;
use Fisharebest\Webtrees\Services\MigrationService;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Webtrees;
use Illuminate\Database\Capsule\Manager as DB;
use PHPUnit\Framework\TestCase;

abstract class AbstractDBTestCase extends TestCase
{
    /** @var LocalizationService $localizationService */
    private $localizationService;

    /** @var Tree $tree */
    private $trees;

    private $db;

    protected function setUp(): void
    {
        parent::setUp();
        // setup mocks
        $i18nMock = test::double(\Fisharebest\Webtrees\I18N::class,
            [
                'init' => 'en_US',
                'translate' => function ($in) {return $in;},
                'direction' => 'ltr',
            ]);
        defined('WT_LOCALE') || define('WT_LOCALE', I18N::init());
        $authMock = test::double(\Fisharebest\Webtrees\Auth::class, ['checkComponentAccess' => null, 'accessLevel' => Auth::PRIV_NONE]);

        $this->db = new DB();
        $this->db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'schema' => $this->getName(),
        ]);
        $this->db->setAsGlobal();
        $migration_service = new MigrationService();
        DB::enableQueryLog();
        $migration_service->updateSchema('\Fisharebest\Webtrees\Schema', 'WT_SCHEMA_VERSION', Webtrees::SCHEMA_VERSION);
        if ($this->getLastQuery() === null) {
            throw new \Exception("Schema upgrade did not run.");
        }
        // Create config data
        $migration_service->seedDatabase();

        $this->localizationService = $this->createMock(LocalizationService::class);
        $tree = Tree::create('name', 'title'); //new Tree(1, 'name', 'title');
        $this->trees[$tree->id()] = $tree;

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->getDatabaseManager()->purge();
        Site::$preferences = array();

        test::clean(); // remove all registered test doubles
    }

    protected function getTree(int $id = 1): ?Tree
    {
        return $this->trees[$id];
    }

    /**
     * Returns a mock LocalizationService.
     *
     * @return LocalizationService
     */
    protected function getLocalizationService(): LocalizationService
    {
        return $this->localizationService;
    }

    protected function getLastQuery(): ?array
    {
        $query_log = DB::getQueryLog();
        return array_pop($query_log);
    }
}
