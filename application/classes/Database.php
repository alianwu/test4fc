<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Database extends Kohana_Database {

	public function quote($value)
	{
		if ($value === NULL)
		{
			return 'NULL';
		}
		elseif ($value === TRUE)
		{
			return "'1'";
		}
		elseif ($value === FALSE)
		{
			return "'0'";
		}
		elseif (is_object($value))
		{
			if ($value instanceof Database_Query)
			{
				// Create a sub-query
				return '('.$value->compile($this).')';
			}
			elseif ($value instanceof Database_Expression)
			{
				// Compile the expression
				return $value->compile($this);
			}
			else
			{
				// Convert the object to a string
				return $this->quote( (string) $value);
			}
		}
		elseif (is_array($value))
		{
			return '('.implode(', ', array_map(array($this, __FUNCTION__), $value)).')';
		}
		elseif (is_int($value))
		{
			return (int) $value;
		}
		elseif (is_float($value))
		{
			// Convert to non-locale aware float to prevent possible commas
			return sprintf('%F', $value);
		}
		elseif (is_string($value) && ($_value = substr($value, 0, 5)) === '__&__')
		{
      // for function
			return substr($value, 5);
		}

		return $this->escape($value);
	}
}
