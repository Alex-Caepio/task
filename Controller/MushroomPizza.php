<?php

class MushroomPizza extends Pizza
{
    public function calculatePrice($size)
    {
        switch ($size) {
            case '21':
                return $this->price;
            case '26':
                return $this->price + 5;
            case '31':
                return $this->price + 7;
            case '45':
                return $this->price + 9;
            default:
                return $this->price;
        }
    }
}