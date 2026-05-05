<?php

use PHPUnit\Framework\TestCase;
use App\Math;

class MathTest extends TestCase
{
    private Math $math;

    protected function setUp(): void
    {
        $this->math = new Math();
    }

    public function testAdd()
    {
        $this->assertEquals(5, $this->math->add(2, 3));
        $this->assertEquals(0, $this->math->add(-2, 2));
        $this->assertEquals(-5, $this->math->add(-2, -3));
    }

    public function testSubtract()
    {
        $this->assertEquals(1, $this->math->subtract(3, 2));
        $this->assertEquals(-1, $this->math->subtract(2, 3));
        $this->assertEquals(0, $this->math->subtract(5, 5));
    }

    public function testMultiply()
    {
        $this->assertEquals(6, $this->math->multiply(2, 3));
        $this->assertEquals(-6, $this->math->multiply(-2, 3));
        $this->assertEquals(0, $this->math->multiply(5, 0));
    }

    public function testDivide()
    {
        $this->assertEquals(2.5, $this->math->divide(5, 2));
        $this->assertEquals(-3, $this->math->divide(-6, 2));
    }

    public function testDivideByZero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->math->divide(5, 0);
    }
}