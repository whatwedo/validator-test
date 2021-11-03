# ValidatorTest
Fluid Validator Tester, inspired by zenstruck/browser.



## Usage

```php
class AppUserValidatorTest extends KernelTestCase
{
    use ValidationTestTrait;


    public function testAppUserRegisterGroup(): void
    {
        $appUser = new AppUser();

        $this->getValidator()

            ->setGroups(['register'])

            ->validate($appUser)
            ->assertCount(4)

            ->assertNotNullTrue('firstname')
            ->validate($appUser->setFirstname('mauri'))
            ->assertNotNullFalse('firstname')

            ->assertCountViolation(4, NotNull::IS_NULL_ERROR);

            ->assertHasViolation(NotNull::IS_NULL_ERROR)
            ->assertHasNoViolation(NotBlank::IS_BLANK_ERROR)

            ->validate($appUser->setEmail('mauri')
            ->assertViolationTrue(Email::INVALID_FORMAT_ERROR, 'email')
            ->validate($appUser->setEmail('mauri@whatwedo.ch'))
            ->assertViolationFalse(Email::INVALID_FORMAT_ERROR, 'email')

           ;
    }

```

### use

```php

        $this->getValidator()
            ->setGroups(['register'])
            ->validate($appUser)
            ->use(function (ConstraintViolationListInterface $constraintViolationList) {
                /*..*/
            })
        ;

```



