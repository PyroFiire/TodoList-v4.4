<?php

namespace App\Tests;

use Symfony\Component\Validator\ConstraintViolation;

trait HelperEntityTrait
{
    public function assertHasErrors(object $object, int $number = 0): void
    {
        self::bootKernel();
        $validator = self::$container->get('validator');
        $errors = $validator->validate($object);
        $messages = [];

        foreach ($errors as $error) {
            /* @var ConstraintViolation $error */
            $messages[] = strtoupper($error->getPropertyPath()).' => '.$error->getMessage();
        }

        $this->assertCount($number, $errors, implode(' ||| ', $messages));
    }

    public function getText(int $number): string
    {
        $text = '';
        for ($i = 0; $i < $number; ++$i) {
            $text = $text.'a';
        }

        return $text;
    }
}
