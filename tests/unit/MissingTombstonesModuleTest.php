<?php

declare (strict_types = 1);

namespace bmarwell\WebtreesModules\MissingTombstones;

use AspectMock\Test as test;
use PHPUnit\Framework\TestCase;
use \Fisharebest\Webtrees\I18N;
use \Fisharebest\Webtrees\Tree;
use \Fisharebest\Webtrees\User;
use \Fisharebest\Webtrees\Module\ModuleThemeInterface;

use Psr\Http\Message\ResponseInterface;

class MissingTombstonesModuleTest extends TestCase
{

    /**
     * @var Tree|\PHPUnit\Framework\MockObject\MockObject
     */
    private $tree;

    /** @var TombstoneListService mockListService */
    private $mockListService;

    /** @var MissingTombstonesModule module */
    private $module;

    protected function setUp(): void
    {
         $view = test::double('\Fisharebest\Webtrees\View', ['make' => function($arg) {
            return ''; // test::spec('\Fisharebest\Webtrees\Module\ModuleThemeInterface', []);
        }]);
        $i18nMock = test::double('\Fisharebest\Webtrees\I18N',
            [
                'init' => 'en_US',
                'translate' => function ($in) {return $in;},
                'direction' => 'ltr',
            ]);
        $authMock = test::double('\Fisharebest\Webtrees\Auth', ['checkComponentAccess' => null ]);

        $this->tree = $this->createMock(Tree::class);
        $this->mockListService = $this->createMock(TombstoneListService::class);
        $this->module = new MissingTombstonesModule($tombstoneListService = $this->mockListService);
        $this->module->setName("webtrees-missingtombstones");
        $this->module->boot();

        $responseMock = $this->createMock(ResponseInterface::class);
        //$responseMock->method('viewArgs')->will($this->returnCallback());
        $this->module = test::double($this->module, ['viewResponse' => function($viewName, $viewArgs) use ($responseMock) {
            return $responseMock;
        }]);
        defined('WT_LOCALE') || define('WT_LOCALE', I18N::init());
    }

    protected function tearDown(): void
    {
        test::clean(); // remove all registered test doubles
    }

    public function testInitModule(): void
    {
        $this->assertNotNull($this->module, "module should be initializable.");
    }

    public function testLoadSearch(): void
    {
        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method('getAttributes')
            ->willReturn(array(
                'years' => 30,
            ));
        $request->method('getAttribute')
            ->with('years')
            ->willReturn(30);
        $user = $this->createMock(User::class);
        $listView = $this->module->getListAction($request, $this->tree, $user);

        $args = $this->module->getCallsForMethod('viewResponse')[0];

        $viewName = $args[0];
        $this->assertEquals('webtrees-missingtombstones::list', $viewName);

        $viewArgs = $args[1];
        $this->assertEquals('Missing Tombstones', $viewArgs['title']);
        $this->assertEquals(array(), $viewArgs['individuals']);
        $this->assertEquals(30, $viewArgs['numYears']);
        $this->assertEquals($this->tree, $viewArgs['tree']);
    }

}
