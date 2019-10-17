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

// do not use namespace here.
// namespace bmhm\WebtreesModules\MissingTombstones;

use bmhm\WebtreesModules\MissingTombstones\MissingTombstonesModule;

spl_autoload_register(function ($class) {
  $cwd = dirname(__FILE__);
  $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
  $localFile = $cwd . '/' . str_replace('bmhm/WebtreesModules/MissingTombstones', 'src', $file);

  if (file_exists($localFile)) {
      require $localFile;
      return true;
  }

  if (file_exists($file)) {
      require $file;
      return true;
  }

  return false;
});

return new MissingTombstonesModule();
