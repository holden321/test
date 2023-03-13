<?php

session_start();

class App
{
	const ERROR_EMPTY_MEMBERS = 'Введите значение';
	const ERROR_BAD_NAME = 'Неправильное имя';
	const ERROR_NAME_EXISTS = 'Имя уже существует';

	private $members = [];

	public function __construct()
	{
		$this->members = $_SESSION["members"] ?? [];
	}

	public function getMembers()
	{
		return $this->members;
	}

	public function addMembers($rawMembers)
	{
		$result = [];
		$members = trim($rawMembers);
		if (empty($members)) {
			throw new \Exception(self::ERROR_EMPTY_MEMBERS);
		}
		$members = $this->array_iunique(array_map('trim', explode(',', $rawMembers)));
		foreach ($members as $name) {
			$this->validateMemberName($name);
			$this->checkExists($name);
		}
		foreach ($members as $name) {
			$this->members[] = [
				'id' => count($this->members) + 1,
				'name' => $name,
				'points' => rand(0, 1000),
			];
		}
		$_SESSION["members"] = $this->members;
		$result['members'] = $this->members;
		return $result;
	}

	function array_iunique($array)
	{
		return array_intersect_key(
			$array,
			array_unique(array_map("mb_strtolower", $array))
		);
	}

	private function validateMemberName($name)
	{
		if (!preg_match('/^[ЁёА-я ]+$/ui', $name)) {
			throw new \Exception(self::ERROR_BAD_NAME . ': ' . $name);
		}
	}

	private function checkExists($name)
	{
		foreach ($this->members as $member) {
			if (mb_strtolower($member['name']) == mb_strtolower($name)) {
				throw new \Exception(self::ERROR_NAME_EXISTS . ': ' . $name);
			}
		}
	}

}

if (isset($_POST['action'])) {

	header('Content-Type: application/json');

	$result = [
		'error' => false,
		'result' => [],
	];

	$app = new App();

	try {
		switch ($_POST['action']) {
			case 'load':
				$result['result'] = $app->getMembers();
				break;
			case 'add_members':
				$result['result'] = $app->addMembers($_POST['members']);
				break;
			default:
				throw new \Exception("No such action: {$_POST['action']}");
		}
	} catch (\Exception $e) {
		$result['error'] = $e->getMessage();
	}
	echo json_encode($result);
	die;
}

include 'page.php';