<?php

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

namespace PTK\TType;

use ArrayObject;
use Exception;
use PTK\Exceptlion\Type\InvalidTypeException;

/**
 * TList: uma lista indexada (a.k.a. array com chaves inteiras).
 *
 * @author Everton
 */
class TList extends ArrayObject implements TMixed
{
    
    public function __construct(array $items = [])
    {
        try {
            $this->checkKey(array_keys($items));
        } catch (Exception $ex) {
            throw $ex;
        }
        
        parent::__construct($items);
    }
    
    protected function checkKey(array $keys): void
    {
        
        foreach ($keys as $k) {
            if (is_int($k) === false) {
                throw new InvalidTypeException(gettype($k), ['int']);
            }
        }
    }
    
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
