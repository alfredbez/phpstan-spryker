<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\PhpStan\DynamicType;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\Business\AbstractFacade;

class FacadeDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getFactory' => true,
    ];

    /**
     * @return string
     */
    public static function getClass(): string
    {
        return AbstractFacade::class;
    }
}
