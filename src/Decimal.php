<?php
	/** @noinspection PhpUnusedPrivateMethodInspection */
	/** @noinspection PhpUnused */
	
	namespace YetAnother;

	/**
	 * Represents a decimal value, defaulting to a high precision of six decimal places.
	 * @package App\Library
	 */
	class Decimal extends ImmutableDecimal
	{
		/**
		 * Adds a value to the decimal value.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function add($value): self
		{
			$this->value = bcadd($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Subtracts a value from the decimal value.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function sub($value): self
		{
			$this->value = bcsub($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Multiples the decimal by a value (mutable).
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function mul($value): self
		{
			$this->value = bcmul($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Divides the decimal by a value (mutable).
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function div($value): self
		{
			$result = bcdiv($this->value, self::valueFrom($value, $this->precision), $this->precision);
			if ($result === null)
				throw new DecimalException('Decimal divide operation failed.');
			$this->value = $result;
			return $this;
		}
	}
