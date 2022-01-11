<?php

declare(strict_types=1);

namespace whatwedo\ValidationTest\Test;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use \whatwedo\ValidationTest\ValidatorTester;

trait ValidationTestTrait
{
    private ?ValidatorTester $validatorTester = null;

    public function getValidator(): ValidatorTester
    {
        if (!$this->validatorTester) {
            $this->validatorTester = new ValidatorTester(self::getContainer()->get(ValidatorInterface::class));
        }
        return $this->validatorTester;
    }

    public function newValidator(): ValidatorTester
    {
        $this->validatorTester = new ValidatorTester(self::getContainer()->get(ValidatorInterface::class));
        return $this->validatorTester;
    }
}
