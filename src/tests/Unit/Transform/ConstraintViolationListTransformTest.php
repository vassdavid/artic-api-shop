<?php
namespace App\Tests\Unit\Transform;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Transform\ConstraintViolationListTransform;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ConstraintViolationListTransform::class)]
class ConstraintViolationListTransformTest extends TestCase
{
    public function testTransformArray(): void
    {

        /** Set violations example */
        $violation1 = new ConstraintViolation('Error message 1', null, [], 'root', 'property1', 'value1');
        $violation2 = new ConstraintViolation('Error message 2', null, [], 'root', 'property2', 'value2');

        $violationList = new ConstraintViolationList([$violation1, $violation2]);

        $transformedArray = ConstraintViolationListTransform::transfromArray($violationList);

        $this->assertEquals(
            [
                [
                    'property' => 'property1',
                    'message' => 'Error message 1',
                ],
                [
                    'property' => 'property2',
                    'message' => 'Error message 2',
                ],
            ],
            $transformedArray
        );
    }
}
