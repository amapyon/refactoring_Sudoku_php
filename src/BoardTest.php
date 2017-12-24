<?php

require_once 'Board.php';

class BoardTest extends PHPUnit_Framework_TestCase
{

    private $board;

    protected function setUp()
    {
        $this->board = new Board();
    }

    protected function tearDown()
    {
        $this->board = null;
    }

    public function testCanPut()
    {
        $this->assertTrue($this->board->canPut(1, 1, 1), '最初は置ける');
         $this->board->put(1, 1, 1);
         $this->assertFalse($this->board->canPut(4, 1, 1), '横列には同じ値は置けない');
         $this->assertTrue($this->board->canPut(4, 1, 2), '横列に違う値ならば置ける');
         $this->assertFalse($this->board->canPut(1, 4, 1), '縦列には同じ値は置けない');
         $this->assertTrue($this->board->canPut(1, 4, 2), '縦列に違う値ならば置ける');
         $this->assertFalse($this->board->canPut(2, 2, 1), '同じブロックに同じ値は置けない');
         $this->assertTrue($this->board->canPut(2, 2, 2), '同じブロックに違う値ならば置ける');
    }

    public function testPutGet()
    {
        $this->assertEquals(0, $this->board->getNumber(1, 1), '初期値は0');
        $this->board->put(1, 1, 1);
        $this->assertEquals(1, $this->board->getNumber(1, 1), '置いた値が取得できる');
        $this->board->put(1, 1, 2);
        $this->assertEquals(2, $this->board->getNumber(1, 1), '値の上書きが可能');
    }

    public function testClear()
    {
        $this->board->put(9, 9, 9);
        $this->board->clear(9, 9);
        $this->assertEquals(0, $this->board->getNumber(9, 9), '0にクリアされる');
    }

    public function testCanPutPositionException()
    {
        try {
            $this->board->canPut(0, 1, 1);
            $this->fail('列座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->canPut(10, 1, 1);
            $this->fail('列座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->canPut(1, 0, 1);
            $this->fail('行座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->canPut(1, 10, 1);
            $this->fail('行座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}
    }

    public function testCanPutValueException()
    {
        try {
            $this->board->canPut(1, 1, 0);
            $this->fail('値が小さすぎる場合は、例外が発生');
        } catch(IllegalNumber $expected) {}

        try {
            $this->board->canPut(1, 1, 10);
            fail('値が大きすぎる場合は、例外が発生');
        } catch(IllegalNumber $expected) {}
    }

    public function testPutPositionException()
    {
        try {
            $this->board->put(0, 1, 1);
            $this->fail('列座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->put(10, 1, 1);
            $this->fail('列座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->put(1, 0, 1);
            $this->fail('行座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->put(1, 10, 1);
            $fail('行座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}
    }

    public function testPutValueException()
    {
        try {
            $this->board->put(1, 1, 0);
            $this->fail('値が小さすぎる場合は、例外が発生');
        } catch(IllegalNumber $expected) {}

        try {
            $this->board->put(1, 1, 10);
            $this->fail('値が大きすぎる場合は、例外が発生');
        } catch(IllegalNumber $expected) {}
    }

    public function testGetNumberPositionException()
    {
        try {
            $this->board->getNumber(0, 1);
            $this->fail('列座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->getNumber(10, 1);
            $this->fail('列座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->getNumber(1, 0);
            $this->fail('行座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->getNumber(1, 10);
            $this->fail("行座標が大きすぎる場合は、例外が発生");
        } catch(OutOfBoard $expected) {}
    }

    public function testClearPositionException()
    {
        try {
            $this->board->clear(0, 1);
            $this->fail('列座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->clear(10, 1);
            $this->fail('列座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->clear(1, 0);
            $this->fail('行座標が小さすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}

        try {
            $this->board->clear(1, 10);
            fail('行座標が大きすぎる場合は、例外が発生');
        } catch(OutOfBoard $expected) {}
    }

    public function testIsComplete()
    {
        $this->assertFalse($this->board->isComplete());

        $this->board->put(1, 1, 1); $this->board->put(2, 1, 4); $this->board->put(3, 1, 7);
        $this->board->put(4, 1, 2); $this->board->put(5, 1, 5); $this->board->put(6, 1, 8);
        $this->board->put(7, 1, 3); $this->board->put(8, 1, 6); $this->board->put(9, 1, 9);

        $this->board->put(1, 2, 2); $this->board->put(2, 2, 5); $this->board->put(3, 2, 8);
        $this->board->put(4, 2, 3); $this->board->put(5, 2, 6); $this->board->put(6, 2, 9);
        $this->board->put(7, 2, 1); $this->board->put(8, 2, 4); $this->board->put(9, 2, 7);

        $this->board->put(1, 3, 3); $this->board->put(2, 3, 6); $this->board->put(3, 3, 9);
        $this->board->put(4, 3, 1); $this->board->put(5, 3, 4); $this->board->put(6, 3, 7);
        $this->board->put(7, 3, 2); $this->board->put(8, 3, 5); $this->board->put(9, 3, 8);

        $this->board->put(1, 4, 4); $this->board->put(2, 4, 7); $this->board->put(3, 4, 1);
        $this->board->put(4, 4, 5); $this->board->put(5, 4, 8); $this->board->put(6, 4, 2);
        $this->board->put(7, 4, 6); $this->board->put(8, 4, 9); $this->board->put(9, 4, 3);

        $this->board->put(1, 5, 5); $this->board->put(2, 5, 8); $this->board->put(3, 5, 2);
        $this->board->put(4, 5, 6); $this->board->put(5, 5, 9); $this->board->put(6, 5, 3);
        $this->board->put(7, 5, 4); $this->board->put(8, 5, 7); $this->board->put(9, 5, 1);

        $this->board->put(1, 6, 6); $this->board->put(2, 6, 9); $this->board->put(3, 6, 3);
        $this->board->put(4, 6, 4); $this->board->put(5, 6, 7); $this->board->put(6, 6, 1);
        $this->board->put(7, 6, 5); $this->board->put(8, 6, 8); $this->board->put(9, 6, 2);

        $this->board->put(1, 7, 7); $this->board->put(2, 7, 1); $this->board->put(3, 7, 4);
        $this->board->put(4, 7, 8); $this->board->put(5, 7, 2); $this->board->put(6, 7, 5);
        $this->board->put(7, 7, 9); $this->board->put(8, 7, 3); $this->board->put(9, 7, 6);

        $this->board->put(1, 8, 8); $this->board->put(2, 8, 2); $this->board->put(3, 8, 5);
        $this->board->put(4, 8, 9); $this->board->put(5, 8, 3); $this->board->put(6, 8, 6);
        $this->board->put(7, 8, 7); $this->board->put(8, 8, 1); $this->board->put(9, 8, 4);

        $this->board->put(1, 9, 9); $this->board->put(2, 9, 3); $this->board->put(3, 9, 6);
        $this->board->put(4, 9, 7); $this->board->put(5, 9, 1); $this->board->put(6, 9, 4);
        $this->board->put(7, 9, 8); $this->board->put(8, 9, 2); $this->board->put(9, 9, 5);
        $this->assertTrue($this->board->isComplete());
    }

    public function testInit()
    {
        $expected = <<< EOM
+---+---+---+
|...|...|...|
|...|...|...|
|...|...|...|
+---+---+---+
|...|...|...|
|...|...|...|
|...|...|...|
+---+---+---+
|...|...|...|
|...|...|...|
|...|...|...|
+---+---+---+
EOM;
        $this->assertEquals($expected, $this->board);
    }

    public function testToString()
    {
        $expected =
        "+---+---+---+".PHP_EOL.
        "|1..|...|..9|".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "+---+---+---+".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "+---+---+---+".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "|...|...|...|".PHP_EOL.
        "|...|...|..9|".PHP_EOL.
        "+---+---+---+";
        $this->board->put(1, 1, 1);
        $this->board->put(9, 1, 9);
        $this->board->put(9, 9, 9);
        $this->assertEquals($expected, $this->board);
    }

}
