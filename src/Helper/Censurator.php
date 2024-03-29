<?php

namespace App\Helper;

use App\Entity\Wish;

class Censurator
{
    private $censured;

    public function __construct() {
        $this->censured = ['merde', 'con', 'salop'];
    }
    public function purify(string $text):string {

        foreach ($this->censured as $word){
            $text = str_ireplace($word, str_repeat('*', mb_strlen($word)), $text);
        }
        return $text;
    }
}