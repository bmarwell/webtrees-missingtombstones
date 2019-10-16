<?php

declare(strict_types=1);

namespace bmarwell\WebtreesModules\MissingTombstones;

use Fisharebest\Localization\Locale\LocaleEn;
use Fisharebest\Localization\Locale\LocaleInterface;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Services\MigrationService;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\User;
use Fisharebest\Webtrees\Webtrees;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\TestCase;


class ModuleTest extends TestCase
{


    /**
     * @var Tree|\PHPUnit\Framework\MockObject\MockObject
     */
    private $tree;

    protected function setUp(): void
    {
        $this->tree = $this->createMock(Tree::class);
        $this->initGlobals();
        $this->createTestDatabase();
        I18N::init();
    }

    protected static function createTestDatabase(): void
    {
        $capsule = new DB();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $capsule->setAsGlobal();
        Builder::macro('whereContains', function ($column, string $search, string $boolean = 'and'): Builder {
            $search = strtr($search, ['\\' => '\\\\', '%' => '\\%', '_' => '\\_', ' ' => '%']);
            return $this->where($column, 'LIKE', '%' . $search . '%', $boolean);
        });
        // Migrations create logs, which requires an IP address, which requires a request
        // Create tables
        $migration_service = new MigrationService();
        $migration_service->updateSchema('\Fisharebest\Webtrees\Schema', 'WT_SCHEMA_VERSION', Webtrees::SCHEMA_VERSION);
        // Create config data
        $migration_service->seedDatabase();
    }


    public function testInitModule()
    {
        $module = new MissingTombstonesModule();

        $this->assertNotNull($module, "module should be initializable.");
    }

    public function testLoadSearch()
    {
        $module = new MissingTombstonesModule();
        $module->setName("webtrees-missingtombstones");


        \Illuminate\Container\Container::getInstance()->bind(LocaleInterface::class, LocaleEn::class, $shared = true);
        \Illuminate\Container\Container::getInstance()->bind(Tree::class, function () {
            return $this->tree;
        });

        $module->boot();
        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method('getAttributes')
            ->willReturn(array(
                'years' => 30,
            ));
        $request->method('getAttribute')
            ->with('years')
            ->willReturn(30);
        $user = $this->createMock(User::class);
        $listView = $module->getListAction($request, $this->tree, $user);

        print_r($listView);
    }

    private function initGlobals()
    {
        $resolver = function () {
            $someFunction = function () {
            };
            $return = $this->createMock(\Illuminate\Support\Collection::class);
            $mockRepo = $this->getMockBuilder(\Illuminate\Cache\Repository::class)
                ->disableOriginalConstructor()
                ->getMock();
            $mockRepo->expects($this->once())
                ->method('rememberForever')
                ->with($this->logicalOr(
                    $this->equalTo('all_modules'),
                    $this->equalTo('module_privacy0')
                ), $someFunction)
                ->willReturn($this->returnCallback(array($this, function () {
                })));
            return $mockRepo;
        };
        \Illuminate\Container\Container::getInstance()->resolving('cache.array', $resolver);
        \Illuminate\Container\Container::getInstance()->bind('cache.array', $resolver, $shared = true);
    }
}

