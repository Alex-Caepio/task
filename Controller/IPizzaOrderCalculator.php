<?php

interface IPizzaOrderCalculator
{
    public function calculateOrderPrice($pizzaType, $pizzaSize, $sauce);
}
