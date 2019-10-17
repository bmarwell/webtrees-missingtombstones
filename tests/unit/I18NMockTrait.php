<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use AspectMock\Test as test;
use Fisharebest\Webtrees\Webtrees;

trait I18NMockTrait
{
    protected function initI18N(): void
    {
        $i18nMock = test::double(\Fisharebest\Webtrees\I18N::class,
            [
                'init' => 'en_US',
                'translate' => function ($in) {return $in;},
                'translateContext' => function ($ctx, $in) {return $in;},
                'strtoupper' => function ($in) {return strtr($in, self::DOTLESS_I_TOUPPER);},
                'direction' => 'ltr',
            ]);
        defined('WT_LOCALE') || define('WT_LOCALE', I18N::init());
    }
}
