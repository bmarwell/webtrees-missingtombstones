# Missing tombstones module for webtrees


## Contents

* [Introduction](#introduction)
* [Installation](#installation)
* [Verification](#verification)
* [Links](#links)
* [License](#license)

### Introduction

This module for webtrees (currently 1.6.x only) will create a list of persons, deceased in the past 30 years,
who do not have a media type = tombstone attached. This is useful, if you need to add data which can be found
on tombstones, gravestones, headstones (like birth year, year of death, maiden name). Headstones are also a historical and 
[cultural property](https://en.wikipedia.org/wiki/Cultural_property), which should be preserved.


### Installation
1. Copy the folder src/missingtombstones (only the subfolder) to your webtrees/modules_v3-folder.
2. Go to the admin menu and modules, then enable the Missing Tombstones-Module.

### Verification
To see if it works, open this link: 
    module.php?mod=missingtombstones&mod_action=general

There is no menu link yet, and no parameter for the number of years.

### Links
* Forum discussion: [Webtrees: Missing Tombstones](To be done).
* The parent project’s website is [webtrees.net](http://webtrees.net).
* German Description: [Webtrees-Module](https://www.bmarwell.de/projekte/webtrees-module/).

#### Links for finding tombstones online
* For Germany: [grabsteine.genealogy.net](http://grabsteine.genealogy.net).
* Czech Gravestones: [Same link](http://grabsteine.genealogy.net/cemlist.php?n=CZ).
* Monument Project (German): [Monument Project](http://www.denkmalprojekt.org/).
* Irish Gravestone Photographs: [From Ireland/Gravestones](http://www.from-ireland.net/free-gravestone-photographs/).
* Canadian Headstones: [Find a Grave in Canada](http://canadianheadstones.com/findagrave.htm).
* International, but mostly US-American: [Find a Grave](http://www.findagrave.com/). 
* International Directory: [Gravestone Photographic Resource](http://www.gravestonephotos.com/).


### License
webtrees missing tombstones: online genealogy missing tombstones-module.
Copyright (C) 2015 webtrees development team

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
 along with this program. If not, see <http://www.gnu.org/licenses/>.