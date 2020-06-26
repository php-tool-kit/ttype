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

/**
 * Representa um inteiro.
 *
 * @author Everton
 */
class TInt implements TNumber
{
    /**
     *
     * @var int O número.
     */
    protected int $number = 0;
/**
     *
     * @param int $number
     */
    public function __construct(int $number = 0)
    {
        $this->number = $number;
    }

    /**
     * Retorna o número.
     *
     * @return int
     */
    public function get(): int
    {
        return $this->number;
    }

    /**
     * Retorna uma representação string do número.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->get();
    }

    /**
     * Formata o número.
     *
     * @param TInt|null $precision
     * @param TString|null $decimalSeparator
     * @param TString|null $thousandsSeparator
     * @return TString
     */
    public function format(
        ?TInt $precision = null,
        ?TString $decimalSeparator = null,
        ?TString $thousandsSeparator = null
    ): TString {
        $number = $this->get();
        if (is_null($precision)) {
            $precision = new TInt(0);
        }

        if (is_null($decimalSeparator)) {
            $localenv = localeconv();
            $decimalSeparator = new TString($localenv['decimal_point']);
        }

        if (is_null($thousandsSeparator)) {
            $localenv = localeconv();
            $thousandsSeparator = new TString($localenv['thousands_sep']);
        }

        return new TString(
            number_format(
                $number,
                $precision->get(),
                $decimalSeparator->get(),
                $thousandsSeparator->get()
            )
        );
    }

    /**
     * Retorna a precisão.
     *
     * @return TInt
     */
    public function getPrecision(): TInt
    {
        return new TInt(0);
    }
}
