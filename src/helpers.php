<?php
	/** @noinspection PhpUnused */
	
	use YetAnother\Decimal;
	use YetAnother\DecimalException;
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 0 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d0($value = '0'): Decimal
	{
		return Decimal::from($value, 0);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 1 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d1($value = '0'): Decimal
	{
		return Decimal::from($value, 1);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 2 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d2($value = '0'): Decimal
	{
		return Decimal::from($value, 2);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 3 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d3($value = '0'): Decimal
	{
		return Decimal::from($value, 3);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 4 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d4($value = '0'): Decimal
	{
		return Decimal::from($value, 4);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 5 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d5($value = '0'): Decimal
	{
		return Decimal::from($value, 5);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 6 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d6($value = '0'): Decimal
	{
		return Decimal::from($value, 6);
	}
