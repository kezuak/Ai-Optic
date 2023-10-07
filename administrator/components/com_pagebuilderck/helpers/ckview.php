<?php
Namespace Pagebuilderck;

defined('_JEXEC') or die('Restricted access');

class CKView {

	protected $name;

	protected $model;

	protected $input;

	protected $item;

	protected $state;

	protected $items;

	protected $pagination;

	public function __construct() {
		$this->input = CKFof::getInput();
		// check if the user has the rights to access this page
		if (	(CKFof::isAdmin() 
				|| $this->input->get('layout') == 'edit' 
				|| $this->input->get('task') == 'edit')
				&& !CKFof::userCan('edit')) {
			CKFof::_die();
		}
	}

	/**
	 * Magic method since PHP 8.2
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set(string $name, mixed $value): void {

	}

	public function display($tpl = 'default') {
		if ($tpl === null) $tpl = 'default';
		if ($this->model) {
			$this->state = $this->model->getState();
			$this->pagination = $this->model->getPagination();
		}

		$tpl = $this->input->get('layout', $tpl);
		require_once PAGEBUILDERCK_BASE_PATH . '/views/' . strtolower($this->name) . '/tmpl/' . $tpl . '.php';
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public function get($func, $params = array()) {
		$model = $this->getModel();
		if ($model === false) return false;
		$funcName = 'get' . ucfirst($func);
		return $model->$funcName($params);
	}

	public function getModel() {
		if (empty($this->model)) {
			$file = PAGEBUILDERCK_BASE_PATH . '/models/' . strtolower($this->name) . '.php';
			if (! file_exists($file)) return false;
			require_once($file);
			$className = '\Pagebuilderck\CKModel' . ucfirst($this->name);
			$this->model = new $className;
		}
		return $this->model;
	}
}