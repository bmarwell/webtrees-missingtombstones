<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use AspectMock\Test as test;
use Fisharebest\Webtrees\Services\LocalizationService;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\Tree;
use Illuminate\Database\Capsule\Manager as DB;
use PHPUnit\Framework\TestCase;

abstract class AbstractDBTestCase extends TestCase
{
    use DBMockTrait;
    use I18NMockTrait;
    use AuthMockTrait;
    use CacheArrayMockTrait;

    /** @var LocalizationService $localizationService */
    private $localizationService;

    /** @var Tree[] $tree */
    private $trees;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initDB();
        $this->initI18N();
        $this->initAuth();
        $this->initCacheArray();

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

    /**
     * Returns a mock LocalizationService.
     *
     * @return LocalizationService
     */
    protected function getLocalizationService(): LocalizationService
    {
        return $this->localizationService;
    }

    protected function getTree(int $id = 1): ?Tree
    {
        return $this->trees[$id];
    }
}
