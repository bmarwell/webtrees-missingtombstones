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

use Fisharebest\Webtrees\Controller\SearchController;
use Fisharebest\Webtrees\Database;
use Fisharebest\Webtrees\Date;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Media;

class TombstoneSearch extends SearchController {

    /**
     * @param Individual $person
     * @return Media[]
     * @throws \Exception
     */
    private static function findMedia($person) {
        global $WT_TREE;

		$media = array();
		$matches = array();
		
		preg_match_all('/\n(\d) OBJE @(' . WT_REGEX_XREF . ')@/', $person->getGedcom(), $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
			$mediafound = Media::getInstance($match[2], $WT_TREE);
			if (null === $media) {
				continue;
			}
			$media[] =
		}
		
		return $media;
	}

	/**
	 * @param Individual $person
	 * @return bool
     */
	private static function personHasTombstone($person) {
		$linkedMedia = static::findMedia($person);
		foreach ($linkedMedia as $media) {
			if ($media->getMediaType() === "tombstone") {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Perform the search
	 *
	 * @return array of individuals.
	 */
	public function advancedSearch($startyear = null) {
        global $WT_TREE;

		if (empty($startyear)) {
			$startyear = date("Y") - 30;
		}
		
		$myindilist = array();
		$bind = array();
		$date = new Date($startyear);
	
		// Dynamic SQL query, plus bind variables
		$sql = "SELECT DISTINCT 
					ind.i_id AS xref, 
					ind.i_file AS gedcom_id, 
					ind.i_gedcom AS gedcom 
				FROM 
					`##individuals` ind
				JOIN 
					`##dates`  i_d ON (i_d.d_file=ind.i_file AND i_d.d_gid=ind.i_id)
				WHERE 
					ind.i_file=?
					AND i_d.d_fact='DEAT' 
					AND i_d.d_type='@#DGREGORIAN@' 
					AND i_d.d_julianday1>=?";
		$bind[] = $WT_TREE->getTreeId();
		$bind[] = $date->minimumJulianDay();

		$rows = Database::prepare($sql)
				->execute($bind)->fetchAll();
		
		foreach ($rows as $row) {
			$person = Individual::getInstance($row->xref, $WT_TREE);
			
			if (!static::personHasTombstone($person)) {
				$myindilist[] = $person;
			}
			
			// next one
		}
		
		$this->myindilist = $myindilist;
	}
}

