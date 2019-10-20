<?php

declare (strict_types = 1);

namespace bmhm\WebtreesModules\MissingTombstones;

use AspectMock\Test as test;
use Fisharebest\Webtrees\Auth;

trait AuthMockTrait
{

    protected function initAuth(): void
    {
        $authMock = test::double(Auth::class, [
            'checkComponentAccess' => null,
            'accessLevel' => Auth::PRIV_NONE,
            'user' => null,
            'isEditor' => true,
        ]);
    }

}
