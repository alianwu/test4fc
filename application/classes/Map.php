<?php defined('SYSPATH') or die('No direct script access.');

abstract class Map {

  public static $default = 'baidu';
  public static $instances = array();

  protected $_config = array();
  protected $_output = array(
      'lat' => NULL,
      'lng' => NULL,
      'address' => NULL,
      'street' => NULL,
      'street_number' => NULL,
      'district' => NULL,
      'city' => NULL,
      'province' => NULL,
      'level' => NULL,
      'confidence' => NULL,
    );

  public static function instance($group = NULL)
  {
		if ($group === NULL)
		{
			// Use the default setting
			$group = Map::$default;
		}

		if (isset(Map::$instances[$group]))
		{
			// Return the current group if initiated already
			return Map::$instances[$group];
		}

		$config = Kohana::$config->load('map');

		if ( ! $config->offsetExists($group))
		{
			throw new Map_Exception(
				'Failed to load Kohana Map group: :group',
				array(':group' => $group)
			);
		}

		$config = $config->get($group);

		// Create a new map type instance
		$map_class = 'Map_'.ucfirst($config['driver']);
		Map::$instances[$group] = new $map_class($config);

		// Return the instance
		return Map::$instances[$group]; 
  }
  
  protected function __construct(array $config)
	{
		$this->config($config);
  }

  public function config($key = NULL, $value = NULL)
	{
		if ($key === NULL)
			return $this->_config;

		if (is_array($key))
		{
			$this->_config = $key;
		}
		else
		{
			if ($value === NULL)
				return Arr::get($this->_config, $key);

			$this->_config[$key] = $value;
		}

		return $this;
	}

  public function getgeo_ip() {
  
  }

  abstract public function getgeo($location, $reverse) ;

} // End Map
