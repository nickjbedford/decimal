<?php
	/** @noinspection PhpUnused */
	
	namespace YetAnother;

	use Exception;

	/**
	 * Represents a decimal value, defaulting to a high precision of six decimal places.
	 * @package App\Library
	 */
	class Decimal
	{
		/** @var string $value */
		private string $value;

		/** @var int $precision */
		private int $precision;

		const DefaultPrecision = 6;

		/**
		 * Creates a Decimal from a mixed value.
		 * @param mixed $mixed
		 * @param int|null $precision
		 * @return self
		 * @throws DecimalException
		 */
		public static function from($mixed, ?int $precision = null): self
		{
			$precision = $precision ?? self::DefaultPrecision;
			if ($mixed instanceof Decimal)
				return $mixed->copy($precision);
			return new self(self::valueFrom($mixed, $precision), $precision);
		}

		/**
		 * Gets the most appropriate s
		 * @param mixed $mixed
		 * @param int|null $precision
		 * @return self
		 * @throws DecimalException
		 */
		public static function valueFrom($mixed, ?int $precision = null): string
		{
			if ($mixed instanceof Decimal)
				$mixed = $mixed->value;
			try
			{
				return bcadd($mixed ?? '0', '0', $precision ?? self::DefaultPrecision);
			}
			catch(Exception $exception)
			{
				throw new DecimalException($exception->getMessage(), $exception->getCode(), $exception);
			}
		}

		/**
		 * Initialises a new decimal instance.
		 * @param string $value
		 * @param int $precision
		 */
		public function __construct(string $value = '0', int $precision = self::DefaultPrecision)
		{
			$this->precision = max(0, $precision);
			$this->value = bcadd($value, 0, $this->precision);
		}

		/**
		 * @return string
		 */
		public function __toString(): string
		{
			return $this->value();
		}

		/**
		 * @return array
		 */
		public function __debugInfo(): array
		{
			return [
				'value' => $this->value,
				'precision' => $this->precision
			];
		}

		/**
		 * Prints the value.
		 * @param string $format
		 * @param int|null $precision
		 * @return self
		 */
		public function printValue(string $format = '%s', ?int $precision = null): self
		{
			printf($format, $this->value($precision));
			return $this;
		}

		/**
		 * Creates a copy of the decimal.
		 * @param int|null $precision
		 * @return Decimal
		 */
		public function copy(?int $precision = null): Decimal
		{
			return new self($this->value, $precision ?? $this->precision);
		}

		/**
		 * Gets and optionally sets the precision of the decimal.
		 * @return int
		 */
		public function precision(): int
		{
			return $this->precision;
		}

		/**
		 * Gets and optionally sets the precision of the decimal.
		 * @param int $precision
		 * @return self
		 */
		public function toPrecision(int $precision): self
		{
			$precision = max(0, $precision);
			return new self($this->value, $precision);
		}

		/**
		 * Gets the value, optionally converted to a new precision factor.
		 * @param int|null $precision
		 * @return string
		 */
		public function value(?int $precision = null): string
		{
			if ($precision !== null)
				return bcadd($this->value, 0, $precision);
			return $this->value;
		}

		/**
		 * Adds a value to the decimal.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		private function add($value): self
		{
			$this->value = bcadd($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Subtracts a value from the decimal.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		private function sub($value): self
		{
			$this->value = bcsub($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Multiples the decimal by a value.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		private function mul($value): self
		{
			$this->value = bcmul($this->value, self::valueFrom($value, $this->precision), $this->precision);
			return $this;
		}

		/**
		 * Divides the decimal by a value.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		private function div($value): self
		{
			$result = bcdiv($this->value, self::valueFrom($value, $this->precision), $this->precision);
			if ($result === null)
				throw new DecimalException('Decimal divide operation failed.');
			$this->value = $result;
			return $this;
		}

		/**
		 * Returns a new decimal with the value added to it.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function plus($value): self
		{
			return $this->copy()->add($value);
		}

		/**
		 * Returns a new decimal with a value subtracted from it.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function minus($value): self
		{
			return $this->copy()->sub($value);
		}

		/**
		 * Returns a new decimal with the value multiplied by another.
		 * @param mixed $factor
		 * @return self
		 * @throws DecimalException
		 */
		public function times($factor): self
		{
			return $this->copy()->mul($factor);
		}

		/**
		 * Returns a new decimal with the value divided by another.
		 * @param mixed $divisor
		 * @return self
		 * @throws DecimalException
		 */
		public function dividedBy($divisor): self
		{
			return $this->copy()->div($divisor);
		}

		/**
		 * Returns the modulus of the decimal with a divisor.
		 * @param mixed $divisor
		 * @return $this
		 * @throws DecimalException
		 */
		public function modulus($divisor): self
		{
			return new self(bcmod($this->value, self::valueFrom($divisor), $this->precision), $this->precision);
		}

		/**
		 * Determines if the decimal is equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function equals($value): bool
		{
			$value = self::valueFrom($value, $this->precision);
			return bccomp($this->value, $value, $this->precision) == 0;
		}

		/**
		 * Determines if the decimal is not equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function notEquals($value): bool
		{
			$value = self::valueFrom($value, $this->precision);
			return bccomp($this->value, $value, $this->precision) != 0;
		}

		/**
		 * Determines if the decimal is less than another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function lessThan($value): bool
		{
			return bccomp($this->value, self::valueFrom($value, $this->precision), $this->precision) < 0;
		}

		/**
		 * Determines if the decimal is less than or equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function lessThanOrEqual($value): bool
		{
			return bccomp($this->value, self::valueFrom($value, $this->precision), $this->precision) <= 0;
		}

		/**
		 * Determines if the decimal is greater than another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function greaterThan($value): bool
		{
			return bccomp($this->value, self::valueFrom($value, $this->precision), $this->precision) > 0;
		}

		/**
		 * Determines if the decimal is greater than or equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function greaterThanOrEqual($value): bool
		{
			return bccomp($this->value, self::valueFrom($value, $this->precision), $this->precision) >= 0;
		}

		/**
		 * Returns the smaller value of the two as a new decimal.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function min($value): self
		{
			$value = self::valueFrom($value, $this->precision);
			return new self(bccomp($this->value, $value, $this->precision) < 0 ? $this->value : $value);
		}

		/**
		 * Returns the larger value of the two as a new decimal.
		 * @param mixed $value
		 * @return self
		 * @throws DecimalException
		 */
		public function max($value): self
		{
			$value = self::valueFrom($value, $this->precision);
			return new self(bccomp($this->value, $value, $this->precision) > 0 ? $this->value : $value);
		}

		/**
		 * Returns the decimal rounded to a certain precision.
		 * @param int $precision
		 * @return self
		 * @noinspection PhpRedundantOptionalArgumentInspection
		 */
		public function round(int $precision = 0): self
		{
			$multiplier = bcpow(10, $precision + 1, 0);
			$whole = bcmul($this->value, $multiplier, 0);
			$mod = intval(bcmod($whole, 10));
			$whole = $mod < 5 ?
				bcsub($whole, $mod, 0) :
				bcadd($whole, 10 - $mod, 0);
			return new self(bcdiv($whole, $multiplier, $this->precision), $this->precision);
		}

		/**
		 * Returns the decimal, rounded down to the nearest whole number.
		 * @return self
		 */
		public function floor(): self
		{
			return new self(bcsub($this->value, bcmod($this->value, '1', $this->precision), $this->precision), $this->precision);
		}

		/**
		 * Returns the decimal, rounded up to the nearest whole number.
		 * @return self
		 * @throws DecimalException
		 */
		public function ceil(): self
		{
			$floor = $this->floor();
			if ($floor->equals($this->value))
				return $floor;
			return $floor->add(1);
		}

		/**
		 * Determines if the decimal is a whole number.
		 * @return bool
		 * @throws DecimalException
		 */
		public function isInteger(): bool
		{
			return $this->equals($this->floor());
		}
	}
