<?php
	/** @noinspection PhpUnusedPrivateMethodInspection */
	/** @noinspection PhpUnused */
	
	namespace YetAnother;

	use DivisionByZeroError;
	
	/**
	 * Represents a decimal value, defaulting to a high precision of six decimal places.
	 */
	class Decimal extends ImmutableDecimal
	{
		/**
		 * Adds a value to the decimal value.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function add(mixed $value): static
		{
			$this->value = bcadd($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Subtracts a value from the decimal value.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function sub(mixed $value): static
		{
			$this->value = bcsub($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Multiples the decimal by a value (mutable).
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function mul(mixed $value): static
		{
			$this->value = bcmul($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Divides the decimal by a value (mutable).
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function div(mixed $value): static
		{
			try
			{
				$this->value = bcdiv($this->value, self::valueFrom($value, $this->precision), $this->precision);
				return $this;
			}
			catch (DivisionByZeroError $exception)
			{
				throw new DecimalException('Decimal divide operation failed.', 0, $exception, $value);
			}
		}
	}
