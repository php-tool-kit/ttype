<?php

use PHPUnit\Framework\TestCase;
use PTK\Exceptlion\RegEx\InvalidPatternException;
use PTK\Exceptlion\Value\InvalidValueException;
use PTK\TType\TDict;
use PTK\TType\TFloat;
use PTK\TType\TInt;
use PTK\TType\TList;
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
 * TEstes para TString
 *
 * @author Everton
 */
class TStringTest extends TestCase {

    public function testConstruct() {
        $this->assertInstanceOf(TString::class, new TString());
    }

    public function testGet() {
        $str = 'Hello world';
        $obj = new TString($str);
        $this->assertEquals($str, $obj->get());
    }

    public function testToString() {
        $str = 'Hello world';
        $obj = new TString($str);
        $this->assertEquals($str, (string) $obj);
    }

    public function testLength() {
        $str = 'Hello world';
        $obj = new TString($str);
        $this->assertEquals(11, $obj->length());
    }

    public function testSize() {
        $str = 'Hello world';
        $obj = new TString($str);
        $this->assertEquals(11, $obj->size());
    }

    public function testTemplate() {
        $str = '{hello} {world} TString';
        $obj = new TString($str);
        $merged = $obj->template(new TDict([
                    'hello' => 'Hello',
                    'world' => 'World'
        ]));
        $this->assertEquals('Hello World TString', $merged);
        $this->assertInstanceOf(TString::class, $merged);
    }

    public function testFormat() {
        $str = '%s %s TString';
        $obj = new TString($str);
        $merged = $obj->format(new TList(['Hello', 'World']));
        $this->assertEquals('Hello World TString', $merged);
        $this->assertInstanceOf(TString::class, $merged);
    }
    
    public function testSplit() {
        $str = 'Hello world';
        $obj = new TString($str);
        $splited = new TList(str_split($str, 1));
        $this->assertEquals($splited, $obj->split());
        $this->assertInstanceOf(TList::class, $obj->split());
        
        $splited = new TList(str_split($str, 2));
        $this->assertEquals($splited, $obj->split(2));
    }
    
    public function testMerge() {
        $obj = new TString('hello');
        $merged = $obj->merge(new TString(' world'), new TString(' TString'));
        $this->assertInstanceOf(TString::class, $merged);
        $this->assertEquals('hello world TString', $merged->get());
    }
    
    public function testJoin() {
        $obj = new TString('hello');
        $joined = $obj->join(new TString(' '), new TString('world'), new TString('TString'));
        $this->assertInstanceOf(TString::class, $joined);
        $this->assertEquals('hello world TString', $joined->get());
    }
    
    public function testToFloat() {
        $obj = new TString('R$ 1.998,77');
        $number = $obj->toFloat(new TString(','));
        
        $this->assertInstanceOf(TFloat::class, $number);
        $this->assertEquals(1998.77, $number->get());
    }
    
    public function testToFloatInvalidDecimalSeparator() {
        $obj = new TString('R$ 1.998,77');
        $this->expectException(InvalidValueException::class);
        $number = $obj->toFloat(new TString('!'));
    }
    
    public function testToFloatNoSeparator() {
        $obj = new TString('R$ 1.998');
        $number = $obj->toFloat(new TString(','));
        
        $this->assertInstanceOf(TFloat::class, $number);
        $this->assertEquals(1998.00, $number->get());
    }
    
    public function testToInt() {
        $obj = new TString('R$ 1.998,77');
        $number = $obj->toInt();
        
        $this->assertInstanceOf(TInt::class, $number);
        $this->assertEquals(199877, $number->get());
    }
    
    public function testToNumberFloat() {
        $obj = new TString('R$ 1.998,77');
        $number = $obj->toNumber(new TString(','));
        
        $this->assertInstanceOf(TFloat::class, $number);
        $this->assertEquals(1998.77, $number->get());
    }
    
    public function testToNumberInt() {
        $obj = new TString('R$ 1.998,77');
        $number = $obj->toNumber();
        
        $this->assertInstanceOf(TInt::class, $number);
        $this->assertEquals(199877, $number->get());
    }
    
    public function testToUpperCase() {
        $obj = new TString('hello world');
        $str = $obj->uppercase();
        
        $this->assertInstanceOf(TString::class, $str);
        $this->assertEquals('HELLO WORLD', $str->get());
    }
    
    public function testToLowerCase() {
        $obj = new TString('HELLO WORLD');
        $str = $obj->lowercase();
        
        $this->assertInstanceOf(TString::class, $str);
        $this->assertEquals('hello world', $str->get());
    }
    
    public function testToUCFirst() {
        $obj = new TString('hello world');
        $str = $obj->ucfirst();
        
        $this->assertInstanceOf(TString::class, $str);
        $this->assertEquals('Hello world', $str->get());
    }
    
    public function testToCaseTitle() {
        $obj = new TString('hello world');
        $str = $obj->caseTitle();
        
        $this->assertInstanceOf(TString::class, $str);
        $this->assertEquals('Hello World', $str->get());
    }
    
    public function testSubstring() {
        $obj = new TString('hello world');
        $str = $obj->substring(new TInt(3), new TInt(2));
        
        $this->assertInstanceOf(TString::class, $str);
        $this->assertEquals('lo', $str->get());
    }
    
    public function testMatch() {
        $obj = new TString('hello world TString');
        $matches = $obj->match(new TString('/hello|TString|foo/i'));
        
        $this->assertInstanceOf(TList::class, $matches);
        $this->assertEquals([[
            ['hello', 0],
            ['TString', 12]
        ]], $matches->toArray());
    }
    
    public function testMatchEmpty() {
        $obj = new TString('hello world TString');
        $matches = $obj->match(new TString('/foo/i'));
        
        $this->assertInstanceOf(TList::class, $matches);
        $this->assertEquals([[]], $matches->toArray());
    }
    
    public function testMatchInvalidPattern() {
        $obj = new TString('hello world TString');
        $this->expectException(InvalidPatternException::class);
        $matches = $obj->match(new TString('/hello'));
    }
    
    public function testReplace() {
        $obj = new TString('foo world');
        $match = $obj->replace(new TString('/foo/'), new TString('hello'));
        
        $this->assertInstanceOf(TString::class, $match);
        $this->assertEquals('hello world', $match->get());
    }
    
    public function testReplaceInvalidPattern() {
        $obj = new TString('foo world');
        $this->expectException(InvalidPatternException::class);
        $matches = $obj->replace(new TString('/foo'), new TString('hello'));
    }

}
