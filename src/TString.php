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
 * TString: string
 *
 * @author Everton
 */
class TString implements TMixed {

    /**
     *
     * @var string A string.
     */
    protected string $data = '';

    /**
     * 
     * @param string $data Uma string. Se omitido, uma string vazia será usada.
     */
    public function __construct(string $data = '') {
        $this->data = $data;
    }

    /**
     * Devolve a string.
     * 
     * @return string
     */
    public function get(): string {
        return $this->data;
    }

    /**
     * Devolve a string.
     * 
     * @return string
     */
    public function __toString(): string {
        return $this->get();
    }
    
    /**
     * Retorna o tamanho de uma string.
     * 
     * @return int
     * @link https://www.php.net/manual/pt_BR/ref.strings.php strlen()
     */
    public function length(): int {
        return strlen($this->get());
    }
    
    /**
     * Apelido para TString::length()
     * @return int
     * @see TString::length()
     */
    public function size(): int {
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
    public function template(TDict $data): TString {
        foreach ($data as $key => $value){
            $this->data = str_replace('{'.$key.'}', $value, $this->get());
        }
        return $this;
    }
    
    /**
     * Mescla dados à string.
     * 
     * @param TList $data
     * @return TString
     * @link https://www.php.net/manual/en/function.sprintf.php sprintf()
     * @see TString::template()
     */
    public function format(TList $data): TString {
        $this->data = sprintf($this->get(), ...$data);
        return $this;
    }
    
    /**
     * Divide a string em pedaçõs.
     * 
     * @param int $chunk
     * @return TList
     * @link https://www.php.net/manual/pt_BR/function.str-split.php str_split()
     */
    public function split(int $chunk = 1): TList
    {
        return new TList(str_split($this->get(), $chunk));
    }
    
    /**
     * Adiciona várias TString à string atual.
     * 
     * @param TString $pieces
     * @return TString
     */
    public function merge(TString ...$pieces): TString
    {
        
        $this->join(new TString(''), ...$pieces);
        
        return $this;
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
        $this->data = \join($glue, [$this->data, ...$pieces]);
        
        return $this;
    }
}
