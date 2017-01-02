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

use Composer\Autoload\ClassLoader;

use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Menu;

use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleConfigInterface;
use Fisharebest\Webtrees\Module\ModuleReportInterface;

use Fisharebest\Webtrees\Controller\PageController;

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
  public function __construct() {
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
    return I18N::translate("Missing Tombstones");
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return I18N::translate("Creates a list about missing tombstones for individuals deceased recently.");
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
    header("X-Module-Tombstone: empty");

    switch ($modAction) {
      case 'admin_config':
        $this->showAdmin();
        break;
      default :
        global $WT_TREE;
        $numYears = $WT_TREE->getPreference('mod_mt_years', $default = "30");

        global $controller;
        $controller = new TombstoneSearch();
        $controller->setPageTitle("Tombstone Report")
          ->pageHeader()
          ->addExternalJavascript(WT_STATIC_URL . 'js/autocomplete.js')
          ->addInlineJavascript('autocomplete();');
        $controller->action = "general";
        $controller->query = "missingtombstones";
        $controller->advancedSearch($numYears);

        echo "\n<h1>Missing tombstones for the deceased of the last " . $numYears . " years</h1>\n";

        $controller->printResults();
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigLink() {
    return 'module.php?mod=' . $this->getName() . '&amp;mod_action=admin_config';
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

  public function showAdmin() {
    global $controller;
    $controller = new PageController;
    $controller
      ->restrictAccess(Auth::isAdmin())
      ->setPageTitle($this->getTitle())
      ->pageHeader();

    ?>
    <ol class="breadcrumb small">
      <li><a href="admin.php"><?php echo I18N::translate('Control panel'); ?></a></li>
      <li><a href="admin_modules.php"><?php echo I18N::translate('Module administration'); ?></a></li>
      <li class="active"><?php echo $controller->getPageTitle(); ?></li>
    </ol>

    <h1><?php echo $controller->getPageTitle(); ?></h1>

    <?php

    // Save the updated preferences
    if (Filter::post('action') == 'save') {
      $this->saveTreePreferences();
      echo '<div style="width: 100%; border: 2px solid #FF5E00; padding: .5rem; margin: 1rem 0 1rem; background-color: lightgoldenrodyellow;">';
      echo I18N::translate('Preferences saved');
      echo '</div>';
    }

    ?>

    <p>
      <?php echo I18N::translate('On this page you can set per tree, how many years a tombstone will exist on a cemetery before it gets removed.'); ?>
      <?php echo I18N::translate('For Europe, a grave will last typically about 30 years, unless the family of the deceased pays for prolongation.'); ?>
    </p>

    <form method="post" action="module.php?mod=<?= $this->getName() ?>&amp;mod_action=admin_config">
      <input type="hidden" name="action" value="save">

      <?php foreach (Tree::getAll() as $tree) {  ?>
        <div class="checkbox"><label>
            <input type="number" min="0" max="128" step="1"
                   name="years<?= $tree->getTreeId() ?>"
                   value="<?= $tree->getPreference('mod_mt_years', $default = "30") ?>"
            >
            <?= $tree->getTitleHtml() ?>
          </label></div>

      <?php } ?>

      <input type="submit" value="<?php echo I18N::translate('save'); ?>">
    </form>

    <?php
  }

  protected function saveTreePreferences() {
    foreach (Tree::getAll() as $tree) {
      $tree->setPreference('mod_mt_years', Filter::postInteger('years' . $tree->getTreeId()), $min = 0, $max = 128, $default = 30);
    }
  }
}

return new MissingTombstones();
