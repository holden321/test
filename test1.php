<?php

/**
 * @charset UTF-8
 *
 * Задание 1. Работа с массивами.
 *
 * Есть 2 списка: общий список районов и список районов, которые связаны между собой по географии (соседние районы).
 * Есть список сотрудников, которые работают в определённых районах.
 *
 * Необходимо написать функцию, что выдаст ближайшего сотрудника к искомому району.
 * Если в списке районов, нет прямого совпадения, то должно искать дальше по соседним районам.
 * Необязательное усложнение: выдавать список из сотрудников по близости к искомой функции.
 *
 * Функция должна принимать 1 аргумент: название района (строка).
 * Возвращать: логин сотрудника или null.
 *
 */

class Workers
{

# Использовать данные:

// Список районов
	public $areas = array(
		1 => '5-й поселок',
		2 => 'Голиковка',
		3 => 'Древлянка',
		4 => 'Заводская',
		5 => 'Зарека',
		6 => 'Ключевая',
		7 => 'Кукковка',
		8 => 'Новый сайнаволок',
		9 => 'Октябрьский',
		10 => 'Первомайский',
		11 => 'Перевалка',
		12 => 'Сулажгора',
		13 => 'Университетский городок',
		14 => 'Центр',
	);

// Близкие районы, связь осуществляется по идентификатору района из массива $areas
	public $nearby = array(
		1 => array(2, 11),
		2 => array(12, 3, 6, 8),
		3 => array(11, 13),
		4 => array(10, 9, 13),
		5 => array(2, 6, 7, 8),
		6 => array(10, 2, 7, 8),
		7 => array(2, 6, 8),
		8 => array(6, 2, 7, 12),
		9 => array(10, 14),
		10 => array(9, 14, 12),
		11 => array(13, 1, 9),
		12 => array(1, 10),
		13 => array(11, 1, 8),
		14 => array(9, 10),
	);

// список сотрудников
	public $workers = array(
		0 => array(
			'login' => 'login1',
			'area_name' => 'Октябрьский', //9
		),
		1 => array(
			'login' => 'login2',
			'area_name' => 'Зарека', //5
		),
		2 => array(
			'login' => 'login3',
			'area_name' => 'Сулажгора', //12
		),
		3 => array(
			'login' => 'login4',
			'area_name' => 'Древлянка', //3
		),
		4 => array(
			'login' => 'login5',
			'area_name' => 'Центр', //14
		),
	);


	/**
	 * @param $areaName
	 * @return array
	 */
	public function getOneByArea($areaName)
	{
		$workers = self::getAllByArea($areaName);
		return $workers[0] ?? null;
	}

	/**
	 * @param string $areaName Целевой район
	 * @return array
	 */
	public function getAllByArea($areaName)
	{
		$workers = [];

		if ($areaId = array_flip($this->areas)[$areaName] ?? null) {

			// собираем id районов
			// для работников в том же районе сразу проставляем длину 0
			array_walk($this->workers, function (&$worker) use ($areaId) {
				$workerAreaId = $this->getAreaIdByName($worker['area_name']);
				$worker['path_length'] = $workerAreaId == $areaId ? 0 : null;
				$worker['area_id'] = is_null($worker['path_length']) ? $workerAreaId : null;
			});

			$needAreaIds = array_filter(array_column($this->workers, 'area_id'));

			$paths = [];

			// находим маршруты от целевого района до других
			$tree = [$areaId => $this->makeTree($areaId, $paths, $needAreaIds)];

			// дерево не используем
			// короткие маршруты сохранены
			// считаем их длину и сортируем работников
			foreach ($this->workers as &$worker) {
				if (isset($paths[$worker["area_id"]])) {
					$worker['path_length'] = count($paths[$worker["area_id"]]);
				}
			}
			$workers = array_filter($this->workers, function ($worker) {
				return !is_null($worker['path_length']);
			});

			$workers = array_column($workers, 'path_length', 'login');

			asort($workers);

		}

		return array_keys($workers);
	}

	private function getAreaIdByName($areaName)
	{
		return array_flip($this->areas)[$areaName] ?? null;
	}

	private function makeTree($id, &$paths, &$needAreaIds, $branchIds = [])
	{
		// до первого зацикливания
		$children = array_flip(array_diff($this->nearby[$id] ?? [], $branchIds));

		foreach ($children as $childId => $index) {
			$children[$childId] = $this->makeTree($childId, $paths, $needAreaIds, array_merge($branchIds ?: [$id], [$childId]));
		}

		// конец ветки
		if (!$children) {
			// сохраняем минимальные маршруты для нужных районов
			foreach ($needAreaIds as $needAreaId)
				if ($index = array_search($needAreaId, $branchIds)) {
					$path = array_slice($branchIds, 0, $index + 1);
					// длиной маршрута считаем количество шагов от района до района
					$len = count($path);
					if (!isset($paths[$needAreaId]) || count($paths[$needAreaId]) > $len) {
						$paths[$needAreaId] = $path;
					}
				}
		}

		return $children;
	}
}




$workers = new Workers();

//$result = $workers->getOneByArea('Древлянка');
$result = $workers->getAllByArea('Древлянка');

header('content-type: text/plain; utf-8');
print_r($result);