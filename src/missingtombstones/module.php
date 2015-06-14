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
use WT\Auth;
include_once WT_MODULES_DIR . 'missingtombstones/TombstoneSearch.php';
require_once WT_ROOT . 'includes/functions/functions_print_lists.php';
class missingtombstones_WT_Module extends WT_Module implements WT_Module_Config {
	/*
	 * ***************************
	 * Module configuration
	 * ***************************
	 */
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
		return WT_PRIV_PUBLIC;
	}
	
	/**
	 * {@inheritdoc}
	 */
	function modAction($modAction) {
		header ( "X-Module-Tombstone: empty" );
		
		switch ($modAction) {
			default :
				global $controller;
				$controller = new WT_Controller_Search ();
				$controller->setPageTitle ( "Tombstone Report" )
				->pageHeader ()
				->addExternalJavascript ( WT_STATIC_URL . 'js/autocomplete.js' )
				->addInlineJavascript ( 'autocomplete();' );
				$controller->action = "general";
				$controller->myindilist = $this->getIndividualsWithoutTombstone ();
				$controller->printResults ();
				break;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getConfigLink() {
		return 'module.php?mod=' . $this->getName () . '&amp;mod_action=admin_config';
	}
	
	protected function getIndividualsWithoutTombstone() {
		$ts = new TombstoneSearch ();
		
		return $ts->advancedSearch ();
	}
}