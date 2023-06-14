<?php

require_once 'Pizza.php';

class PepperoniPizza extends Pizza
{
    public function calculatePrice($size)
    {
        switch ($size) {
            case '21':
                return $this->price;
            case '26':
                return $this->price + 2;
            case '31':
                return $this->price + 4;
            case '45':
                return $this->price + 6;
            default:
                return $this->price;
        }
    }
}