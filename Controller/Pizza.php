<?php

abstract class Pizza
{
    protected $type;
    protected $price;

    public function __construct($type, $price)
    {
        $this->type = $type;
        $this->price = $price;
    }

    abstract public function calculatePrice($size);
}