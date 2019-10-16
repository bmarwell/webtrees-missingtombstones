<?php

declare(strict_types=1);

namespace bmarwell\WebtreesModules\MissingTombstones;

use Fisharebest\Localization\Locale\LocaleEn;
use Fisharebest\Localization\Locale\LocaleInterface;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\User;
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

        // \Illuminate\Container\Container::getInstance()->bind('cache.array', array());

        $resolver = function () {
            $someFunction = function () {
            };
            $return = $this->createMock(\Illuminate\Support\Collection::class);
            $mockRepo = $this->getMockBuilder(\Illuminate\Cache\Repository::class)
                ->disableOriginalConstructor()
                ->onlyMethods(array('rememberForever'))
                ->getMock();
            $mockRepo->expects($this->atLeastOnce())
                ->method('rememberForever')
                ->with('module_privacy0', $someFunction)
                ->willReturn($return);
            return $mockRepo;
        };
        \Illuminate\Container\Container::getInstance()->resolving('cache.array', $resolver);
        \Illuminate\Container\Container::getInstance()->bind('cache.array', $resolver, $shared = true);
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
}

