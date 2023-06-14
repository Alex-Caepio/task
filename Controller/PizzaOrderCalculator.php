<?php

require_once 'IPizzaOrderCalculator.php';
require_once 'PepperoniPizza.php';
require_once 'RuralPizza.php';
require_once 'HawaiianPizza.php';
require_once 'MushroomPizza.php';
require_once '../db/DatabaseConnector.php';
require_once '../currency/CurrencyConverter.php';

class PizzaOrderCalculator implements IPizzaOrderCalculator
{
    private $pizzaTypes = [
        'Пепперони' => 'PepperoniPizza',
        'Деревенская' => 'RuralPizza',
        'Гавайская' => 'HawaiianPizza',
        'Грибная' => 'MushroomPizza'
    ];

    private $dbConnector;
    private $currencyConverter;

    public function __construct()
    {
        $this->dbConnector = new DatabaseConnector();
        $this->currencyConverter = new CurrencyConverter();
    }

    public function calculateOrderPrice($pizzaType, $pizzaSize, $sauce)
    {
        if (!array_key_exists($pizzaType, $this->pizzaTypes)) {
            return 0;
        }
        $dbConnector = $this->dbConnector;
        $pizzaClass = $this->pizzaTypes[$pizzaType];
        $pizza = new $pizzaClass($pizzaType, $dbConnector->getPizzaPrice($pizzaType));
        $pizzaPriceUSD = $pizza->calculatePrice($pizzaSize);
        $saucePriceUSD = $dbConnector->getSaucePrice($sauce);

        $exchangeRate = $this->currencyConverter->getExchangeRate();

        if (!$exchangeRate) {
            echo 'Не удалось получить актуальный курс валют.' . '<br/>';
            return 0;
        }

        $pizzaPriceBYN = $pizzaPriceUSD * $exchangeRate;
        $saucePriceBYN = $saucePriceUSD * $exchangeRate;

        $totalPrice = round($pizzaPriceBYN + $saucePriceBYN, 2);

        $orderSummary = "Пицца: $pizzaType<br>";
        $orderSummary .= "Размер: $pizzaSize см<br>";
        $orderSummary .= "Соус: $sauce<br>";
        $orderSummary .= "Цена: $totalPrice BYN";

        return $orderSummary;
    }

}

$orderCalculator = new PizzaOrderCalculator();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pizzaType = htmlspecialchars($_POST['pizzaType']);
    $pizzaSize = htmlspecialchars($_POST['pizzaSize']);
    $sauce = htmlspecialchars($_POST['sauce']);

    $pizzaTerminal = new PizzaOrderCalculator();
    $orderSummary = $pizzaTerminal->calculateOrderPrice($pizzaType, $pizzaSize, $sauce);

    echo $orderSummary;
}
