<?php

/**
 * @charset UTF-8
 *
 * Задание 2. Работа с массивами и строками.
 *
 * Есть список временных интервалов (интервалы записаны в формате чч:мм-чч:мм).
 *
 * Необходимо написать две функции:
 *
 *
 * Первая функция должна проверять временной интервал на валидность
 *    принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 *    возвращать boolean
 *
 *
 * Вторая функция должна проверять "наложение интервалов" при попытке добавить новый интервал в список существующих
 *    принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм). Учесть переход времени на следующий день
 *  возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием одного интервала,
 *   встречается начало, окончание или то и другое одновременно, другого интервала
 *
 *
 *
 *  пример:
 *
 *  есть интервалы
 *    "10:00-14:00"
 *    "16:00-20:00"
 *
 *  пытаемся добавить еще один интервал
 *    "09:00-11:00" => произошло наложение
 *    "11:00-13:00" => произошло наложение
 *    "14:00-16:00" => наложения нет
 *    "14:00-17:00" => произошло наложение
 */


class Intervals
{
	public $items = [];

	public function push($str)
	{
		$interval = Interval::create($str);
		if (!$this->checkIntersects($interval)) {
			$this->items[] = $interval;
			return true;
		}
		return false;
	}

	private function checkIntersects($interval)
	{
		foreach ($this->items as $item) {
			// нестрого, могут соприкасаться
			if ($item->start < $interval->end && $item->end > $interval->start) {
				return true;
			}
		}
		return false;
	}
}

class Interval
{
	public $start;
	public $end;

	public static function create($str)
	{
		if (preg_match('/^(\d\d):(\d\d)-(\d\d):(\d\d)$/', $str, $matches)) {
			try {
				$start = new DateTime("{$matches[1]}:{$matches[2]}");
				$end = new DateTime("{$matches[3]}:{$matches[4]}");
			} catch (\Exception $e) {
				return false;
			}

			// переход на следующий день
			if ($end < $start) {
				date_add($end, date_interval_create_from_date_string("1 day"));
			}
		}

		$interval = new self;
		$interval->start = $start;
		$interval->end = $end;
		return $interval;
	}

	public static function validate($str)
	{
		return !!self::create($str);
	}


}

header('content-type: text/plain; utf-8');
date_default_timezone_set('Europe/Moscow');

$intervals = new Intervals();

$results = [];

$results[] = 'validate intervals';
$results[] = +Interval::validate('22:00-15:59'); // 1
$results[] = +Interval::validate('52:00-15:59'); // 0

$list = array(
	'10:00-14:00',
	'16:00-20:00',
);

foreach ($list as $item) {
	$intervals->push($item);
}
$results[] = 'adding intervals';
$results[] = +$intervals->push('09:00-11:00'); // 0;
$results[] = +$intervals->push('11:00-13:00'); // 0;
$results[] = +$intervals->push('14:00-16:00'); // 1;
$results[] = +$intervals->push('14:00-17:00'); // 0;


echo implode(PHP_EOL, $results);