<?php
class OutOfBoard extends Exception {}
class IllegalNumber extends Exception {}

const BOARD_SIZE = 9;
const BLOCK_SIZE = 3;
const MIN_VALUE = 1;
const MAX_VALUE = 9;
const UNDEFINE_VALUE = 0;
const CORRECT = 9;

class Board
{
    private $cell = [];

    /*
     * コンストラクタ
     */
    public function __construct()
    {
        for ($y = 0; $y < BOARD_SIZE; $y++) {
            for ($x = 0; $x < BOARD_SIZE; $x++) {
                $this->cell[$x][$y] = UNDEFINE_VALUE;
            }
        }
    }

    /*
     * 指定したマスに数字が置けるか判定する
     */
    public function canPut($col, $row, $num)
    {
        // ボードの範囲外のマスを指定していないか検証する
        if ($col < 1) {
            throw new OutOfBoard();
        }
        if ($col > BOARD_SIZE) {
            throw new OutOfBoard();
        }
        if ($row < 1) {
            throw new OutOfBoard();
        }
        if ($row > BOARD_SIZE) {
            throw new OutOfBoard();
        }

        // イレギュラーな数字を指定していないか検証する
        if ($num < MIN_VALUE || $num > MAX_VALUE) {
            throw new IllegalNumber();
        }

        // 座標の補正を行う
        $x = $col - 1;
        $y = $row - 1;

        return $this->canPutHorizontal($y, $num)
        && $this->canPutVertical($x, $num)
        && $this->canPutBlock($x, $y, $num);
    }

    /*
     * ブロック単位で数字が置けるか判定する
     */
    private function canPutBlock($x, $y, $num)
    {
        // ブロックの左上のセルを求める
        $cellLeft = floor($x / BLOCK_SIZE) * BLOCK_SIZE;
        $cellTop = floor($y / BLOCK_SIZE) * BLOCK_SIZE;

        for ($x = $cellLeft; $x < ($cellLeft + BLOCK_SIZE); $x++) {
            for ($y = $cellTop; $y < ($cellTop + BLOCK_SIZE); $y++) {
                if ($this->cell[$x][$y] == $num) {
                    return false;
                }
            }
        }
        return true;
    }

    /*
     * 縦方向で数字が置けるか判定する
     */
    private function canPutVertical($x, $num)
    {
        for ($y = 0; $y < BOARD_SIZE; $y++) {
            if ($this->cell[$x][$y] == $num) {
                return false;
            }
        }
        return true;
    }

    /*
     * 横方向で数字が置けるか判定する
     */
    private function canPutHorizontal($y, $num)
    {
        for ($x = 0; $x < BOARD_SIZE; $x++) {
            if ($this->cell[$x][$y] == $num) {
                return false;
            }
        }
        return true;
    }

    /*
     * 指定したマスに数字を置く
     */
    public function put($col, $row, $num)
    {
        // ボードの範囲外のマスを指定していないか検証する
        if ($col < 1 || $col > BOARD_SIZE) {
            throw new OutOfBoard();
        }
        if ($row < 1 || $row > BOARD_SIZE) {
            throw new OutOfBoard();
        }

        // イレギュラーな数字を指定していないか検証する
        if ($num < MIN_VALUE || $num > MAX_VALUE) {
            throw new IllegalNumber();
        }

        // 座標の補正を行う
        $x = $col - 1;
        $y = $row - 1;

        $this->cell[$x][$y] = $num;
    }

    /*
     * 指定したマスの数字を調べる
     */
    public function getNumber($col, $row)
    {
        // ボードの範囲外のマスを指定していないか検証する
        if ($col < 1 || $col > BOARD_SIZE) {
            throw new OutOfBoard();
        }
        if ($row < 1 || $row > BOARD_SIZE) {
            throw new OutOfBoard();
        }

        // 座標の補正を行う
        $x = $col - 1;
        $y = $row - 1;

        return $this->cell[$x][$y];
    }

    /*
     * 指定したマスから数字を消す
     */
    public function clear($col, $row)
    {
        // ボードの範囲外のマスを指定していないか検証する
        if ($col < 1 || $col > BOARD_SIZE) {
            throw new OutOfBoard();
        }
        if ($row < 1 || $row > BOARD_SIZE) {
            throw new OutOfBoard();
        }

        // 座標の補正を行う
        $x = $col - 1;
        $y = $row - 1;

        $this->cell[$x][$y] = UNDEFINE_VALUE;
    }

    /*
     * ゲーム盤の状態を文字列として返す
     */
    public function __toString()
    {
        $s = '';
        for ($row = 0 ; $row < BOARD_SIZE; $row++) {
            if (($row % BLOCK_SIZE) == 0) {
                $s .= '+---+---+---+'.PHP_EOL;
                for ($col = 0; $col < BOARD_SIZE; $col++) {
                    if (($col % BLOCK_SIZE) == 0) {
                        $s .= '|';
                        $s .= $this->toChar($col, $row);
                    } else {
                        $s .= '';
                        $s .= $this->toChar($col, $row);
                    }
                }
            } else {
                for ($col = 0; $col < BOARD_SIZE; $col++) {
                    if (($col % BLOCK_SIZE) == 0) {
                        $s .= '|';
                        $s .= $this->toChar($col, $row);
                    } else {
                        $s .= '';
                        $s .= $this->toChar($col, $row);
                    }
                }
            }
            $s .= '|'.PHP_EOL;
        }
        $s .= '+---+---+---+';
        return $s;
    }

    private function toChar($x, $y)
    {
        switch ($this->cell[$x][$y]) {
            case 0:
                return '.';
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                return (string)$this->cell[$x][$y];
            default:
                return 'x';
        }
    }

    /*
     * すべてのマスに矛盾なく数字が置いてあるか判定する
     */
    public function isComplete()
    {
        if ($this->isCompleteHorizontal()
            && $this->isCompleteVertical()
            && $this->isCompleteBlocks()) {
                return true;
            }
            return false;
    }

    /*
     * 横方向に数字がもれなく配置されているか確認する
     */
    private function isCompleteBlocks()
    {
        $result = true;
        for ($y = 0; $y < BOARD_SIZE && $result; $y += BLOCK_SIZE) {
            for ($x = 0; $x < BOARD_SIZE && $result; $x += BLOCK_SIZE) {
                $numbers = [];
                for ($yy = $y; $yy < $y + BLOCK_SIZE; $yy++) {
                    for ($xx = $x; $xx < $x + BLOCK_SIZE; $xx++) {
                        $numbers[$this->cell[$xx][$yy]] = 1;
                    }
                }
                if (!$this->isCompleteArray($numbers)) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    /*
     * 縦方向に数字がもれなく配置されているか判定する
     */
    private function isCompleteVertical()
    {
        $result = true;
        for ($x = 0; $x < BOARD_SIZE && $result; $x++) {
            $numbers = [];
            for ($y = 0; $y < BOARD_SIZE; $y++) {
                $numbers[$this->cell[$x][$y]] = 1;
            }
            if (!$this->isCompleteArray($numbers)) {
                $result = false;
            }
        }
        return $result;
    }

    /*
     * 横方向に数字がもれなく配置されているか判定する
     */
    private function isCompleteHorizontal()
    {
        $result = true;
        for ($y = 0; $y < BOARD_SIZE && $result; $y++) {
            $numbers = [];
            for ($x = 0; $x < BOARD_SIZE; $x++) {
                $numbers[$this->cell[$x][$y]] = 1;
            }
            if (!$this->isCompleteArray($numbers)) {
                $result = false;
            }
        }
        return $result;
    }

    /*
     * 配列の中に、1から9isCompleteでの数字が一つずつある場合はtrueを返す
     */
    private function isCompleteArray($numbers)
    {
        $answer = 0;
        foreach ($numbers as $number) {
            $answer += $number;
        }
        if ($answer != CORRECT) {
            return false;
        }
        return true;
    }
}
