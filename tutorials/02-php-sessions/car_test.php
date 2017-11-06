<?php

require_once("Car.php");
$car = new Car();
$car->setTitle("Fiat");

var_dump($car);
echo "Titel ".$car ->title;