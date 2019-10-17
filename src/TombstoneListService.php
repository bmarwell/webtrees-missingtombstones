<?php
/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
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
declare(strict_types=1);

namespace bmhm\WebtreesModules\MissingTombstones;

use Exception;
use Fisharebest\Webtrees\Date;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Services\LocalizationService;
use Fisharebest\Webtrees\Tree;
use Illuminate\Database\Capsule\Manager as DB;

class TombstoneListService {
    /** @var LocalizationService $localization_service */
    private $localization_service;

    /** @var Tree $tree */
    private $tree;

    /**
     * IndividualListService constructor.
     *
     * @param LocalizationService $localization_service
     * @param Tree                $tree
     */
    public function __construct(LocalizationService $localization_service, Tree $tree)
    {
        $this->localization_service = $localization_service;
        $this->tree                 = $tree;
    }

    /**
     * Perform the search
     *
     * @param $numYearsPast
     *    The number of years where headstones are expected to be removed.
     *    Defaults to 30 years.
     *
     * @return Individual[]
     */
    public function individualsWithTombstone($numYearsPast = 30) : array
    {
        $startyear = date("Y") - $numYearsPast;
        $date = new Date("$startyear");

        $query = DB::table('individuals')
            ->where('d_fact', '=', 'DEAT');
            // TODO: And where (julian) date > $date

        $rows = $query->get()->all();

        // check results if already having a tombstone media.
        $myindilist = array();
        foreach ($rows as $row) {
            $person = Individual::getInstance($row->xref, $this->tree);

            if (static::personHasTombstone($person)) {
                // same as array_push($myindilist, $person);
                continue;
            }

            $myindilist[] = $person;
            // next result
        }

        return $myindilist;
    }

    /**
     * @param Individual $person
     * @return Media[]
     * @throws Exception
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

            $media[] = $mediafound;
        }

        return $media;
    }

    /**
     * @param Individual $person
     * @return bool
     */
    public static function personHasTombstone($person) {
        if ($person === NULL) {
            return false;
        }

        $linkedMedia = static::findMedia($person);

        foreach ($linkedMedia as $media) {
            if ($media->getMediaType() === "tombstone") {
                return true;
            }
        }

        return false;
    }
}