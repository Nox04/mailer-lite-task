<?php

namespace Tests\Unit;

use App\Enums\FieldType;
use Tests\TestCase;

/**
 * Class FieldTest
 * @package Tests\Unit
 */
class FieldTest extends TestCase
{
    /**
     * @test
     */
    public function testHasFourTypes()
    {
        $this->assertEquals([
            FieldType::BOOLEAN(),
            FieldType::DATE(),
            FieldType::NUMBER(),
            FieldType::STRING(),
        ], FieldType::getNames());
    }
}
