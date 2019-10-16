<?php
/**
 * webtrees missingtombstone: online genealogy missing-tombstones-module.
 * Copyright (C) 2015 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace bmarwell\WebtreesModules\MissingTombstones;

use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\Contracts\UserInterface;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleConfigInterface;
use Fisharebest\Webtrees\Module\ModuleConfigTrait;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Module\ModuleListInterface;
use Fisharebest\Webtrees\Module\ModuleListTrait;
use Fisharebest\Webtrees\Services\LocalizationService;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MissingTombstones
 * @package bmarwell\WebtreesModules\MissingTombstones
 */
return new class extends AbstractModule implements ModuleListInterface, ModuleConfigInterface
{
    // name() : string set by webtrees.
    use ModuleCustomTrait;

    /**
     * Where does this module store its resources
     *
     * @return string
     */
    public function resourcesFolder(): string
    {
        return __DIR__ . '/resources/';
    }

    /**
     * Bootstrap the module
     */
    public function boot(): void
    {
        // Register a namespace for our views.
        View::registerNamespace($this->name(), $this->resourcesFolder() . 'views/');
    }

    // overrides ModuleInterface::title and AbstractModule::title.
    public function title(): string
    {
        return I18N::translate("Missing Tombstones");
    }

    // overrides ModuleInterface::description and AbstractModule::description.
    public function description(): string
    {
        return I18N::translate("Creates a list about missing tombstones for individuals deceased recently.");
    }

    /* **************************************
     * from interface ModuleCustomInterface *
     * **************************************/
    /**
     * The person or organisation who created this module.
     *
     * @return string
     */
    public function customModuleAuthorName(): string
    {
        return 'Benjamin Marwell';
    }

    /**
     * The version of this module.
     *
     * @return string
     */
    public function customModuleVersion(): string
    {
        return 'wt-2.0.0';
    }

    /**
     * A URL that will provide the latest version of this module.
     *
     * @return string
     */
    public function customModuleLatestVersionUrl(): string
    {
        return 'https://github.com/bmhm/webtrees-missingtombstones';
    }

    /**
     * Where to get support for this module.  Perhaps a github respository?
     *
     * @return string
     */
    public function customModuleSupportUrl(): string
    {
        return 'https://github.com/bmhm/webtrees-missingtombstones';
    }

    /* **************************************
     * from interface ModuleConfigInterface *
     * **************************************/

    use ModuleConfigTrait;
    // defines public function name(): string;
    // defines public function getConfigLink(): string;

    /* ************************************
     * from interface ModuleListInterface *
     * ************************************/

    // defines public function listMenu(Tree $tree): ?Menu;
    // defines public function listMenuClass(): string; // css class name
    // defines public function listUrl(Tree $tree, array $parameters = []): string; // default parameter?
    // defines public function listUrlAttributes(): array; // default rel=nofollow
    // defines public function listIsEmpty(Tree $tree): bool; // keep default=false, because parameter "years" can be infinite.
    use ModuleListTrait;

    // actions are defined as:
    // <httpVerb><actionName>Action, e.g. getAdminAction(), postAdminAction, PutAdminAction, getSearchAction, get…Action, …

    /**
     * Access the admin page using HTTP GET.
     *
     * For more examples, also take a look at SiteMapModule.php.
     * @return ResponseInterface
     */
    public function getAdminAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->layout = 'layouts/administration';

        $missing_tombstone_urls = $this->getListUrls();

        return $this->viewResponse($this->name() . '::admin', [
            'missing_tombstone_urls' => $missing_tombstone_urls,
            'title' => $this->title(),
            'all_trees' => Tree::all(),
        ]);
    }

    public function postAdminAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->layout = 'layouts/administration';

        $missing_tombstone_urls = $this->getListUrls();

        // TODO: actually save!
        // $this->saveTreePreferences($tree);

        return $this->viewResponse($this->name() . '::admin', [
            'missing_tombstone_urls' => $missing_tombstone_urls,
            'title' => $this->title(),
            'all_trees' => Tree::all(),
            'action' => 'save'
        ]);
    }

    function getListUrls() :array
    {
        // TODO: return a URL for each tree.
        return array();
    }

    /**
     * Access the missing tombstones page using HTTP GET.
     *
     * For more examples, also take a look at SiteMapModule.php.
     * @return ResponseInterface
     */
    public function getListAction(ServerRequestInterface $request, Tree $tree, UserInterface $user): ResponseInterface
    {
        Auth::checkComponentAccess($this, ModuleListInterface::class, $tree, $user);

        // TODO: use this as default, also check attribute 'years'.
        $years = $this->getPreference('mod_mt_years');

        // convert to new search format.
        $search = new TombstoneListService(app(LocalizationService::class), app(Tree::class));
        $search->individualsWithTombstone($years);

        $individuals = array();

        return $this->viewResponse($this->name() . '::list', [
            'title' => $this->title(),
            'individuals' => $individuals,
        ]);
    }

};
