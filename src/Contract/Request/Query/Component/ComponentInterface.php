<?php

namespace OneToMany\AI\Contract\Request\Query\Component;

use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;

interface ComponentInterface
{
    public function getRole(): Role;
}
