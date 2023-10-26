<?php
namespace App\Transform;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListTransform
{
    /**
     * @param ConstraintViolationListInterface $violationList
     * @return array<array<string,string>>
     */
    public static function transfromArray(ConstraintViolationListInterface $violationList): array
    {
        $messages = [];
        foreach ($violationList as $violation) {
            $messages[] = [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $messages;
    }
}