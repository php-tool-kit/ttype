<?php

use PHPUnit\Framework\TestCase;
use PTK\TType\TInt;
use PTK\TType\TString;
/*
 * The MIT License
 *
 * Copyright 2020 Everton.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Testes para TInt
 *
 * @author Everton
 */
class TIntTest extends TestCase
{
    public function testConstruct()
    {
        $number = new TInt(1);
        $this->assertInstanceOf(TInt::class, $number);
    }
    
    public function testGet()
    {
        $number = new TInt(1);
        $this->assertEquals(1, $number->get());
    }
    
    public function testToString()
    {
        $number = new TInt(123);
        $this->assertEquals('123', (string) $number);
    }
    
    public function testFormatDefault()
    {
        $number = new TInt(123456);
        $localeconv = localeconv();
        $decSep = $localeconv['decimal_point'];
        $touSep = $localeconv['thousands_sep'];
        $this->assertEquals(number_format(123456, 0, $decSep, $touSep), (string) $number->format());
    }
    
    public function testFormatWithArguments()
    {
        $number = new TInt(123456);
        $decSep = ',';
        $touSep = '.';
        $this->assertEquals(
            number_format(123456, 2, $decSep, $touSep),
                (string) $number->format(new TInt(2),
                new TString($decSep),
                new TString($touSep)));
    }
    
    public function testGetPrecision()
    {
        $number = new TInt(123);
        $precision = $number->getPrecision();
        $this->assertInstanceOf(TInt::class, $precision);
        $this->assertEquals(0, $precision->get());
    }
}
