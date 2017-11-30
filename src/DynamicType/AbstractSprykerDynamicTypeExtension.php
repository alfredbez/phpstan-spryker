<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\PhpStan\DynamicType;

use Exception;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

abstract class AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [];

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     *
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        if (isset($this->methodResolves[$methodReflection->getName()])) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     *
     * @throws \Exception
     *
     * @return \PHPStan\Type\Type
     */
    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $docComment = $scope->getClassReflection()->getNativeReflection()->getDocComment();

        if (!$docComment) {
            throw new Exception('Please add PHPDoc block');
        }

        preg_match_all('#@method\s+(?:(?P<IsStatic>static)\s+)?(?:(?P<Type>[^\(\*]+?)(?<!\|)\s+)?(?P<MethodName>[a-zA-Z0-9_]+)(?P<Parameters>(?:\([^\)]*\))?)#', $docComment, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if ($match['MethodName'] === $methodCall->name) {
                return new ObjectType($match['Type']);
            }
        }

        throw new Exception();
    }
}
