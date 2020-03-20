<?php

namespace App\Tests;

trait HelperEntityTrait
{
    public function assertHasErrors($object, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($object);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = strtoupper($error->getPropertyPath()) . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(' ||| ' , $messages));
    }

    public function getText(int $number): string
    {
        $text = '';
        for ($i=0; $i < $number; $i++) {
            $text = $text.'a';
        }

        return $text;
    }
}