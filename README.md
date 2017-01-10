# Missing tombstones module for webtrees

[![Coverage Status](https://coveralls.io/repos/github/bmhm/webtrees-missingtombstones/badge.svg?branch=master)](https://coveralls.io/github/bmhm/webtrees-missingtombstones?branch=master) [![Build Status](https://travis-ci.org/bmhm/webtrees-missingtombstones.svg?branch=master)](https://travis-ci.org/bmhm/webtrees-missingtombstones) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/9c4d0742754545a3b7bde6d521897f9a)](https://www.codacy.com/app/bmarwell/webtrees-missingtombstones?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bmhm/webtrees-missingtombstones&amp;utm_campaign=Badge_Grade)

## Contents

* [Introduction](#introduction)
  * [Installation](#installation)
  * [Verification](#verification)
* [Usage](#usage)
  * [Settings](#configuration) 
* [Development](#development)
  * [Translation](#translation)
* [Links](#links)
* [License](#license)

## Introduction

This module for webtrees 1.7.x  will create a list of persons, deceased in the past 30 years,
who do not have any media of `type = tombstone` attached. This is useful, if you need to add data which can be found
on tombstones, gravestones, headstones (like birth year, year of death, maiden name). Headstones are also a historical and 
[cultural property](https://en.wikipedia.org/wiki/Cultural_property), which should be preserved.


### Installation
1. Extract the `.tar.bz2` file to your `webtrees/modules_v3`-folder.
2. Go to the admin menu and modules, then enable the Missing Tombstones-Module.

### Verification
To see if it works, open this link: 
    `module.php?mod=missingtombstones&mod_action=general`

There should also be a menu link in the reports menu.

## Usage
From the reports menu, select the link that says "Missing Tombstones". 
You should get a menu which looks like this:

![Missing Tombstones Individual List](/assets/missinglist-de.png)

### Configuration

At the moment you can configure how many years the page should show.
For this, go to the _control panel_, select _Module Administration_ and 
click on the **missing tombstones** entry. You should now see a configuration
page, where you can select the desired amount of years.
 
![Missing Tombstones Settings Page](/assets/settings-en.png)



## Development


### Translation
If you'd like to commit translation files, please send me your `.po` files using a pull request.
You can generate a `messages.pot`-file using `make update` from the command line.

## Links
* Forum discussion: [Webtrees: Missing Tombstones](http://www.webtrees.net/index.php/en/forum/4-customising/30329-missing-tombstones-module#53294).
* The parent project’s website is [webtrees.net](http://webtrees.net).
* German Description: [Webtrees-Module](https://www.bmarwell.de/projekte/webtrees-module/).

### Links for finding tombstones online
* For Germany: [grabsteine.genealogy.net](http://grabsteine.genealogy.net).
* Czech Gravestones: [Same link](http://grabsteine.genealogy.net/cemlist.php?n=CZ).
* Monument Project (German): [Monument Project](http://www.denkmalprojekt.org/).
* Irish Gravestone Photographs: [From Ireland/Gravestones](http://www.from-ireland.net/free-gravestone-photographs/).
* Canadian Headstones: [Find a Grave in Canada](http://canadianheadstones.com/findagrave.htm).
* International, but mostly US-American: [Find a Grave](http://www.findagrave.com/). 
* International Directory: [Gravestone Photographic Resource](http://www.gravestonephotos.com/).


## License
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
