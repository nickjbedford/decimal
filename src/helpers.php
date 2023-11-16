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
	function d0(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 0);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 1 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d1(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 1);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 2 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d2(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 2);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 2 decimal places divided by 100 to create a percentage.
	 * @param mixed $percentageValue
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d2pc(mixed $percentageValue = '0'): Decimal
	{
		return Decimal::from($percentageValue, 2)->dividedBy(100);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 3 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d3(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 3);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 4 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d4(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 4);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 4 decimal places divided by 100 to create a percentage.
	 * @param mixed $percentageValue
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d4pc(mixed $percentageValue = '0'): Decimal
	{
		return Decimal::from($percentageValue, 4)->dividedBy(100);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 5 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d5(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 5);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 6 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d6(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 6);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 6 decimal places divided by 100 to create a percentage.
	 * @param mixed $percentageValue
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d6pc(mixed $percentageValue = '0'): Decimal
	{
		return Decimal::from($percentageValue, 6)->dividedBy(100);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 7 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d7(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 7);
	}
	
	/**
	 * Creates a Decimal from a mixed value with a precision of 8 decimal places.
	 * @param mixed $value
	 * @return Decimal
	 * @throws DecimalException
	 */
	function d8(mixed $value = '0'): Decimal
	{
		return Decimal::from($value, 8);
	}
