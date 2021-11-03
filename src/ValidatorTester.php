<?php

declare(strict_types=1);

namespace whatwedo\ValidationTest;

use PHPUnit\Framework\Assert;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zenstruck\Callback;
use Zenstruck\Callback\Parameter;

class ValidatorTester
{
    /**
     * @var ConstraintViolationListInterface|array
     */
    private ?ConstraintViolationListInterface $violations = null;

    private ?array $groups = null;

    private ValidatorInterface $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    public function setGroups(?array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @param mixed $object
     */
    public function validate($object): self
    {
        $this->violations = $this->validator->validate($object, null, $this->groups);
        return $this;
    }

    public function assertCount(int $expectedCount): self
    {
        Assert::assertCount($expectedCount, $this->violations);
        return $this;
    }

    public function assertCountViolation(int $expectedCount, ?string $code = null): self
    {
        Assert::assertEquals($expectedCount, $this->violationCount($code));

        return $this;
    }

    public function assertHasViolation(?string $code = null): self
    {
        Assert::assertGreaterThan(0, $this->violationCount($code));
        return $this;
    }

    public function assertHasNoViolation(?string $code = null): self
    {
        Assert::assertEquals(0, $this->violationCount($code));
        return $this;
    }

    public function assertViolationTrue(string $code, string $propertyPath = null): self
    {
        Assert::assertTrue($this->hasViolationCode($propertyPath, $code));
        return $this;
    }

    public function assertViolationFalse(string $code, string $propertyPath = null): self
    {
        Assert::assertFalse($this->hasViolationCode($propertyPath, $code));
        return $this;
    }

    public function assertNotNullTrue(?string $propertyPath): self
    {
        return $this->assertViolationTrue(NotNull::IS_NULL_ERROR, $propertyPath);
    }

    public function assertNotNullFalse(?string $propertyPath): self
    {
        return $this->assertViolationFalse(NotNull::IS_NULL_ERROR, $propertyPath);
    }

    /**
     * @return static
     */
    final public function use(callable $callback): self
    {
        Callback::createFor($callback)->invokeAll(
            Parameter::union(
                Parameter::untyped($this->violations),
                Parameter::typed(ConstraintViolationListInterface::class, $this->violations),
            )
        );

        return $this;
    }

    protected function hasViolationCode(string $propertyPath, string $code): bool
    {
        foreach ($this->violations as $violation) {
            if ($violation->getCode() === $code &&
                $violation->getPropertyPath() === $propertyPath) {
                return true;
            }
        }

        return false;
    }

    protected function countViolationProperty(string $propertyPath, string $code): int
    {
        $counter = 0;
        foreach ($this->violations as $violation) {
            if ($violation->getCode() === $code &&
                $violation->getPropertyPath() === $propertyPath) {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * @param string $code
     */
    protected function violationCount(?string $code = null): int
    {
        $counter = 0;
        foreach ($this->violations as $violation) {
            if ($code !== null && $violation->getCode() === $code) {
                $counter++;
            } elseif ($code === null) {
                $counter++;
            }
        }
        return $counter;
    }
}
