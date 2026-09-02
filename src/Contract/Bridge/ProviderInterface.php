<?php

namespace OneToMany\AI\Contract\Bridge;

use OneToMany\AI\ModelVendor;

interface ProviderInterface
{
    public static function getVendor(): ModelVendor;
}
