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

use Fisharebest\webtrees\Auth;

use Composer\Autoload\ClassLoader;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\Menu;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleConfigInterface;
use Fisharebest\Webtrees\Module\ModuleReportInterface;

/**
 * Class MissingTombstones
 * @package bmarwell\WebtreesModules\MissingTombstones
 */
class MissingTombstones extends AbstractModule implements ModuleReportInterface, ModuleConfigInterface {
	/*
	 * ***************************
	 * Module configuration
	 * ***************************
	 */
    /** @var string location of the fancy treeview module files */
    var $directory;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('MissingTombstones');
        $this->directory = WT_MODULES_DIR . $this->getName();
        $this->action = Filter::get('mod_action');
        // register the namespaces
        $loader = new ClassLoader();
        $loader->addPsr4('bmarwell\\WebtreesModules\\MissingTombstones\\', $this->directory);
        $loader->register();
    }

    /**
     * @return string
     */
    public function getName() {
        return "missingtombstones";
    }

	/**
	 * {@inheritdoc}
	 */
	public function getTitle() {
		return "Missing Tombstones";
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDescription() {
		return "Creates a list about missing tombstones for individuals deceased recently.";
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function defaultAccessLevel() {
		return Auth::PRIV_PRIVATE;
	}
	
	/**
	 * {@inheritdoc}
	 */
	function modAction($modAction) {
		header ( "X-Module-Tombstone: empty" );
		
		switch ($modAction) {
			default :
				global $controller;
				$controller = new TombstoneSearch();
				$controller->setPageTitle("Tombstone Report")
				->pageHeader()
				->addExternalJavascript(WT_STATIC_URL . 'js/autocomplete.js')
				->addInlineJavascript('autocomplete();');
				$controller->action = "general";
				$controller->query = "missingtombstones";
				$controller->advancedSearch();
				$controller->printResults();
				break;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getConfigLink() {
		return 'module.php?mod=' . $this->getName () . '&amp;mod_action=admin_config';
	}


	/**
	 * Return a menu item for this report.
	 *
	 * @return Menu
	 */
	public function getReportMenu() {
		global $WT_TREE;

		return new Menu(
			$this->getTitle(),
			'module.php?ged=' . $WT_TREE->getNameUrl() . '&amp;mod=' . $this->getName() . '&amp;mod_action=general',
			'menu-report-' . $this->getName(),
			['rel' => 'follow']
		);
	}
}

return new MissingTombstones();