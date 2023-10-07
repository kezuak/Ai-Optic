<?php
Namespace Pagebuilderck;

defined('_JEXEC') or die('Restricted access');

use Pagebuilderck\CKFof;

class CKModel {

	var $_item = null;

	private static $instance = array();

	protected $input;

	protected $table;

	protected $__state_set = null;

	protected $state;

	protected $pagination;

	private static $name;

	private static $prefix;

	function __construct() {
		$this->input = CKFof::getInput();
		$this->state = new \JObject;
	}

	static function getInstance($name, $prefix, $config = array()) {
		self::$name = $name;
		self::$prefix = $prefix;
		if (isset(self::$instance[$name . $prefix]) && is_object(self::$instance[$name . $prefix]))
		{
			return self::$instance[$name . $prefix];
		}

		$basePath = PAGEBUILDERCK_BASE_PATH;
		// Check for a controller.task command.
		$input = CKFof::getInput();

		// Define the controller filename and path.
		$file       = strtolower($name . '.php');
		$path       = $basePath . '/models/' . $file;

		// Get the controller class name.
		$class = ucfirst($prefix) . 'Model' . ucfirst($name);

		// Include the class if not present.
		if (!class_exists($class))
		{
			// If the controller file path exists, include it.
			if (file_exists($path))
			{
				require_once $path;
			}
			else
			{
//				throw new \InvalidArgumentException(\JText::sprintf('ERROR_INVALID_MODEL', $type, $format));
			
				return false;
			}
		}

		// Instantiate the class.
		if (!class_exists($class))
		{
			throw new \InvalidArgumentException(\JText::sprintf('ERROR_INVALID_MODEL_CLASS', $class));
		}

		// Instantiate the class, store it to the static container, and return it
		return self::$instance[$name . $prefix] = new $class();
	}

	public function save($data) {

	}

	public function delete($id) {
		$return = CKFof::dbDelete( $this->table, (int)$id );
		return $return;
	}

	public function trash($id) {
		$fields = array('state' => '-2');
		$return = CKFof::dbUpdate($this->table, (int)$id, $fields);
		return $return;
	}

	public function setState($property, $value = null)
	{
		return $this->state->set($property, $value);
}

	public function getState($property = null, $default = null)
	{
		if (!$this->__state_set)
		{
			// Protected method to auto-populate the model state.
			$this->populateState();

			// Set the model state set flag to true.
			$this->__state_set = true;
		}

		return $property === null ? $this->state : $this->state->get($property, $default);
	}

	protected function populateState()
	{
		$config = \JFactory::getConfig();
		$state = CKFof::getUserState(self::$prefix . '.' . self::$name, null);

		// first request, or custom user request
		if ($state === null || $this->input->get('state_request', 0, 'int') === 1) {
		$this->state->set('filter_order', $this->input->get('filter_order', 'a.id'));
		$this->state->set('filter_order_Dir', $this->input->get('filter_order_Dir', 'asc'));
		$this->state->set('filter_search', $this->input->get('filter_search', ''));
		$this->state->set('limitstart', $this->input->get('limitstart', 0));
		$this->state->set('limit_total', $this->input->get('limittotal', 0));
		$this->state->set('limit', $this->input->get('limit', $config->get('list_limit')));
			$state = CKFof::setUserState(self::$prefix . '.' . self::$name, $this->state);
		} else {
			$this->state = $state;
		}
	}

	public function getPagination($total = null, $start = null, $limit = null)
	{
		if (!$this->pagination)
		{
			$total = $this->state->get('limit_total', $total);
//			$total = $this->getTotal();
			$start = $this->state->get('limitstart', $start);
			$limit = $this->state->get('limit', $limit);

			$this->pagination = new \JPagination($total, $start, $limit);
		}

		return $this->pagination;
	}

	public function getTotal($query) {
		$db = CKFof::getDbo();
		$query = clone $query;
		$query->clear('select')->clear('order')->clear('limit')->clear('offset')->select('COUNT(*)');
		$db->setQuery($query);

		return (int) $db->loadResult();
	}

	public function copy($id) {
		$row = CKFof::dbLoad($this->table, (int)$id);
		$row->id = 0;
		$row->title = $row->title . ' - copy';

		$newid = CKFof::dbStore($this->table, $row);

		return $newid;
	}

	public function checkout($id) {
		$user = CKFof::getUser();
		$query= "SELECT checked_out from " . $this->table . " WHERE id = " . (int)$id;
		$checkedout = CKFof::dbLoadResult($query);

		if ($checkedout && $checkedout != $user->id) return false;

		return CKFof::dbUpdate($this->table, $id, array('checked_out' => $user->id));
	}

	public function checkin($id) {
		$user		= CKFof::getUser();
		$row = CKFof::dbLoad($this->table, (int)$id);
		$canCheckIn = $row->checked_out == $user->get('id') || CKFof::userCan('core.edit.state');
		if ($canCheckIn) {
		return CKFof::dbUpdate($this->table, $id, array('checked_out' => '0'));
	}
		return false;
	}

	public function publish($id) {
		$row = CKFof::dbLoad($this->table, (int)$id);
		$row->state = 1;

		$result = CKFof::dbStore($this->table, $row);

		return $result;
	}

	public function unpublish($id) {
		$row = CKFof::dbLoad($this->table, (int)$id);
		$row->state = 0;

		$result = CKFof::dbStore($this->table, $row);

		return $result;
	}
}