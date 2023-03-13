<?php

/**
 * @charset UTF-8
 *
 * Задание 3
 * В данный момент компания X работает с двумя перевозчиками
 * 1. Почта России
 * 2. DHL
 * У каждого перевозчика своя формула расчета стоимости доставки посылки
 * Почта России до 10 кг берет 100 руб, все что cвыше 10 кг берет 1000 руб
 * DHL за каждый 1 кг берет 100 руб
 * Задача:
 * Необходимо описать архитектуру на php из методов или классов для работы с
 * перевозчиками на предмет получения стоимости доставки по каждому из указанных
 * перевозчиков, согласно данным формулам.
 * При разработке нужно учесть, что количество перевозчиков со временем может
 * возрасти. И делать расчет для новых перевозчиков будут уже другие программисты.
 * Поэтому необходимо построить архитектуру так, чтобы максимально минимизировать
 * ошибки программиста, который будет в дальнейшем делать расчет для нового
 * перевозчика, а также того, кто будет пользоваться данным архитектурным решением.
 *
 */

# Использовать данные:
# любые


abstract class DeliveryService
{
	protected $name;

	private static $services = [
		1 => 'RussianPost',
		2 => 'DHL',
	];

	abstract public function calc(int $weight);

	public static function create($id)
	{
		$class = self::$services[$id] ?? null;
		if (!class_exists($class)) {
			throw new \Exception("No such service: $id");
		}
		return new $class;
	}
}

class RussianPost extends DeliveryService
{
	public $name = 'Почта России';

	public function calc(int $weight)
	{
		// Почта России до 10 кг берет 100 руб, все что cвыше 10 кг берет 1000 руб
		return $weight < 10 ? 100 : 100 + 1000;
	}
}


class DHL extends DeliveryService
{
	public $name = 'DHL';

	public function calc(int $weight)
	{
		// DHL за каждый 1 кг берет 100 руб
		return $weight * 100;
	}
}


header('content-type: text/plain; utf-8');

$results = [];

$russianPost = DeliveryService::create(1);
$results[] = $russianPost->name;
$results[] = $russianPost->calc(5);
$results[] = $russianPost->calc(200);

$results[] = PHP_EOL;

$DHL = DeliveryService::create(2);
$results[] = $DHL->name;
$results[] = $DHL->calc(5);
$results[] = $DHL->calc(200);


echo implode(PHP_EOL, $results);