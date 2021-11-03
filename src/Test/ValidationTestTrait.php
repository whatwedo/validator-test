<?php

declare(strict_types=1);

namespace whatwedo\ValidationTest\Test;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use \whatwedo\ValidationTest\ValidatorTester;

trait ValidationTestTrait
{
    public function getValidator(): ValidatorTester
    {
        return new ValidatorTester(self::getContainer()->get(ValidatorInterface::class));
    }
}
