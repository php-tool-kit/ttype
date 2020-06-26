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

use PTK\Exceptlion\RegEx\InvalidPatternException;
use PTK\Exceptlion\Value\InvalidValueException;

use function mb_convert_case;
use function mb_str_split;
use function mb_strlen;
use function mb_strtolower;
use function mb_strtoupper;

use const MB_CASE_TITLE;

/**
 * TString: string
 *
 * @author Everton
 */
class TString implements TScalar
{

    /**
     *
     * @var string A string.
     */
    protected string $data = '';

    /**
     *
     * @param string $data Uma string. Se omitido, uma string vazia será usada.
     */
    public function __construct(string $data = '')
    {
        $this->data = $data;
    }

    /**
     * Devolve a string.
     *
     * @return string
     */
    public function get(): string
    {
        return $this->data;
    }

    /**
     * Devolve a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->get();
    }
    
    /**
     * Retorna o tamanho de uma string.
     *
     * @return int
     * @link https://www.php.net/manual/pt_BR/function.mb-strlen.php mb_strlen()
     */
    public function length(): int
    {
        return mb_strlen($this->get());
    }
    
    /**
     * Apelido para TString::length()
     * @return int
     * @see TString::length()
     */
    public function size(): int
    {
        return $this->length();
    }
    
    /**
     * Mescla dados com a string, substituindo qualquer {key} pelo valor em
     * TDict com chave key.
     *
     * @param TDict $data
     * @return TString
     * @see TString::format()
     *
     * @todo Alterar a substiuição para suportar caractere de escape para { e }
     */
    public function template(TDict $data): TString
    {
        $str = $this->get();
        foreach ($data as $key => $value) {
            $str = str_replace('{' . $key . '}', $value, $str);
        }
        return new TString($str);
    }
    
    /**
     * Mescla dados à string.
     *
     * @param TList $data
     * @return TString
     * @link https://www.php.net/manual/en/function.sprintf.php sprintf()
     * @see TString::template()
     */
    public function format(TList $data): TString
    {
        return new TString(sprintf($this->get(), ...$data));
    }
    
    /**
     * Divide a string em pedaçõs.
     *
     * @param int $chunk
     * @return TList
     * @link https://www.php.net/manual/pt_BR/function.mb-str-split.php mb_str_split()
     */
    public function split(int $chunk = 1): TList
    {
        return new TList(mb_str_split($this->get(), $chunk));
    }
    
    /**
     * Adiciona várias TString à string atual.
     *
     * @param TString $pieces
     * @return TString
     */
    public function merge(TString ...$pieces): TString
    {
        
        return new TString(join(new TString(''), [$this->get(), ...$pieces]));
    }
    
    /**
     * Adiciona várias TString à atual usando um separador.
     *
     * @param TString $glue
     * @param TString $pieces
     * @return TString
     */
    public function join(TString $glue, TString ...$pieces): TString
    {
        return new TString(\join($glue, [$this->get(), ...$pieces]));
    }
    
    /**
     * Transformação para float.
     *
     * @param TString $decimalSeparator O separador de decimal.
     * Somente ponto ou vírgula.
     *
     * @return TFloat
     * @throws InvalidValueException
     */
    public function toFloat(TString $decimalSeparator): TFloat
    {
        
        if ($decimalSeparator->get() !== '.' && $decimalSeparator->get() !== ',') {
            throw new InvalidValueException($decimalSeparator, '.|,');
        }
        
        $decSepFound = false;
        $number = '';
        foreach ($this->split() as $chunk) {
            if (preg_match('/[0-9]/', $chunk) === 1) {
                $number .= $chunk;
            }
            
            if ($chunk === $decimalSeparator->get() && $decSepFound === false) {
                $number .= '.';
                $decSepFound = true;
            }
        }
        
        return new TFloat((float) $number);
    }
    
    /**
     * Transformação para inteiro.
     *
     * @return TInt
     */
    public function toInt(): TInt
    {
        
        $number = '';
        foreach ($this->split() as $chunk) {
            if (preg_match('/[0-9]/', $chunk) === 1) {
                $number .= $chunk;
            }
        }
        
        return new TInt((int) $number);
    }
    
    /**
     * Transformação para número. Se o separador de decimal for fornecido, o
     * retorno será float, caso contrário, inteiro.
     *
     * @param TString|null $decimalSeparator
     * @return TNumber
     */
    public function toNumber(?TString $decimalSeparator = null): TNumber
    {
        
        if (is_null($decimalSeparator)) {
            return $this->toInt();
        }
        
        return $this->toFloat($decimalSeparator);
    }
    
    /**
     * Transforma para letras maiúsculas.
     *
     * @return TString
     * @link https://www.php.net/manual/en/function.mb-strtoupper.php mb_strtoupper()
     */
    public function uppercase(): TString
    {
        return new TString(mb_strtoupper($this->get()));
    }
    
    /**
     * Transforma para mínusculas.
     *
     * @return TString
     * @link https://www.php.net/manual/en/function.mb-strtolower.php mb_strtolower()
     */
    public function lowercase(): TString
    {
        return new TString(mb_strtolower($this->get()));
    }
    
    /**
     * Trasnforma a primeira letra da string em maiúscula.
     *
     * @return TString
     * @link https://www.php.net/manual/en/function.ucfirst.php ucfirst()
     */
    public function ucfirst(): TString
    {
        return new TString(ucfirst($this->get()));
    }
    
    /**
     * Transforma todas as primeiras letras em maiúsculas.
     *
     * @return TString
     * @link https://www.php.net/manual/pt_BR/function.mb-convert-case.php mb_convert_case()
     */
    public function caseTitle(): TString
    {
        return new TString(mb_convert_case($this->get(), MB_CASE_TITLE));
    }
    
    /**
     * Retorna uma parte da string.
     *
     * @param TInt $start
     * @param TInt $length
     * @return TString
     * @link https://www.php.net/manual/pt_BR/function.mb-strcut.php mb_strcut()
     */
    public function substring(TInt $start, TInt $length): TString
    {
        return new TString(mb_strcut($this->get(), $start->get(), $length->get()));
    }
    
    /**
     * Retorna todas as correspondências encontradas de acordo com o padrão.
     *
     * @param \PTK\TType\TString $pattern
     * @return \PTK\TType\TList
     * @throws InvalidPatternException
     * @link https://www.php.net/manual/en/function.preg-match-all.php preg_match_all()
     */
    public function match(TString $pattern): TList
    {
        $matches = [];
        
        //suprimido erros para fazer o código de disparar exceção funcionar com PHPUnit
        //se não suprimir, ele lança um erro e não lança a exceção.
        $result = @preg_match_all(
            $pattern->get(),
            $this->get(),
            $matches,
            PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL
        );
        
        if ($result === false || preg_last_error() !== PREG_NO_ERROR) {
            throw new InvalidPatternException($pattern);
        }
        
        return new TList($matches);
    }
    
    /**
     * Substitui no string de acordo com um pattern
     *
     * @param \PTK\TType\TString $pattern
     * @param \PTK\TType\TString $replecement
     * @return \PTK\TType\TString
     * @throws InvalidPatternException
     */
    public function replace(TString $pattern, \PTK\TType\TString $replecement): TString
    {
        //suprimido erros para fazer o código de disparar exceção funcionar com PHPUnit
        //se não suprimir, ele lança um erro e não lança a exceção.
        $match = @preg_replace($pattern->get(), $replecement->get(), $this->get());
        
        if (is_null($match)) {
            throw new InvalidPatternException($pattern->get());
        }
        
        return new TString($match);
    }
}
