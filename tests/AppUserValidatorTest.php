<?php

declare(strict_types=1);

namespace whatwedo\ValidationTest\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use whatwedo\ValidationTest\Test\ValidationTestTrait;

class AppUserValidatorTest extends KernelTestCase
{
    use ValidationTestTrait;

    public function testAppUserRegisterGroup(): void
    {
        $author = new Author();

        $this->getValidator()
            ->validate($author)
            ->assertCount(1)
        ;

    }


}
