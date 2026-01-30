<?php

namespace OneToMany\AI\Contract\Input\Request\Content;

use OneToMany\AI\Contract\Input\Request\Content\Enum\Role;

interface ContentInterface
{
    public function getRole(): Role;
}
