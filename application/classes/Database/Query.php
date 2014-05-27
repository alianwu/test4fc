<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Database query wrapper.  See [Parameterized Statements](database/query/parameterized) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Database_Query extends Kohana_Database_Query {


	// Quoted query parameters
	protected $_parameters_extra = array();


	public function param_extra($param, $value)
	{
		// Add or overload a new parameter
		$this->_parameters_extra[$param] = $value;

		return $this;
	}

	/**
	 * Bind a variable to a parameter in the query.
	 *
	 * @param   string  $param  parameter key to replace
	 * @param   mixed   $var    variable to use
	 * @return  $this
	 */
	public function bind_extra($param, & $var)
	{
		// Bind a value to a variable
		$this->_parameters_extra[$param] =& $var;

		return $this;
	}

	/**
	 * Add multiple parameters to the query.
	 *
	 * @param   array  $params  list of parameters
	 * @return  $this
	 */
	public function parameters_extra(array $params)
	{
		// Merge the new parameters in
		$this->_parameters_extra = $params + $this->_parameters_extra;

		return $this;
	}

	/**
	 * Compile the SQL query and return it. Replaces any parameters with their
	 * given values.
	 *
	 * @param   mixed  $db  Database instance or name of instance
	 * @return  string
	 */
	public function compile($db = NULL)
	{
		if ( ! is_object($db))
		{
			// Get the database instance
			$db = Database::instance($db);
		}

		// Import the SQL locally
		$sql = $this->_sql;

		if ( ! empty($this->_parameters))
		{
			// Quote all of the values
			$values = array_map(array($db, 'quote'), $this->_parameters);

			// Replace the values in the SQL
			$sql = strtr($sql, $values);
		}

    if ( ! empty($this->_parameters_extra)) 
    {
			$sql = strtr($sql, $this->_parameters_extra);
    }

		return $sql;
	}
}
