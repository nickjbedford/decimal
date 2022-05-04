<?php
	/** @noinspection PhpReturnDocTypeMismatchInspection */
	
	namespace YetAnother;

	use Exception;

	/**
	 * Represents an immutable decimal value, defaulting to a high precision of six decimal places.
	 * @package App\Library
	 */
	class ImmutableDecimal
	{
		/** @var string $value */
		protected string $value;

		/** @var int $precision */
		protected int $precision;

		const DefaultPrecision = 6;

		/**
		 * Creates a Decimal from a mixed input value, optionally with a custom precision.
		 * @param mixed $mixed
		 * @param int|null $precision
		 * @return Decimal|ImmutableDecimal
		 * @throws DecimalException
		 */
		public static function from($mixed, ?int $precision = null)
		{
			$precision = $precision ?? self::DefaultPrecision;
			if ($mixed instanceof ImmutableDecimal)
				return $mixed->copy($precision);
			return new static(static::valueFrom($mixed, $precision), $precision);
		}

		/**
		 * Converts a mixed input value a decimal string value usable in the BC math library.
		 * @param mixed $mixed
		 * @param int|null $precision
		 * @return self
		 * @throws DecimalException
		 */
		public static function valueFrom($mixed, ?int $precision = null): string
		{
			if ($mixed instanceof ImmutableDecimal)
				$mixed = $mixed->value;
			try
			{
				return bcadd(strval($mixed) ?? '0', '0', $precision ?? self::DefaultPrecision);
			}
			catch(Exception $exception)
			{
				throw new DecimalException($exception->getMessage(), $exception->getCode(), $exception);
			}
		}

		/**
		 * Initialises a new instance.
		 * @param string $value
		 * @param int $precision
		 */
		public function __construct(string $value = '0', int $precision = self::DefaultPrecision)
		{
			$this->precision = max(0, $precision);
			$this->value = bcadd($value, 0, $this->precision);
		}

		/**
		 * Converts the decimal value to a string.
		 * @return string
		 */
		public function __toString(): string
		{
			return $this->value();
		}

		/**
		 * Gets the debug information about the Decimal instance.
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
		 * Prints the value with an optional custom precision.
		 * @param string $format
		 * @param int|null $precision
		 * @return self
		 * @noinspection PhpUnused
		 */
		public function printValue(string $format = '%s', ?int $precision = null): self
		{
			printf($format, $this->value($precision));
			return $this;
		}

		/**
		 * Creates a copy of the decimal with an optional change to the precision.
		 * @param int|null $precision
		 * @return ImmutableDecimal|Decimal
		 */
		public function copy(?int $precision = null)
		{
			return new static($this->value, $precision ?? $this->precision);
		}

		/**
		 * Gets the precision of the decimal value.
		 * @return int
		 */
		public function precision(): int
		{
			return $this->precision;
		}

		/**
		 * Creates a copy of the decimal with a new precision.
		 * @param int $precision
		 * @return self
		 */
		public function toPrecision(int $precision): self
		{
			$precision = max(0, $precision);
			return new static($this->value, $precision);
		}

		/**
		 * Gets the decimal value as a string, optionally formatted to a new precision factor.
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
		 * Returns a new decimal with the value added to it.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function plus($value)
		{
			return new static(bcadd($this->value, static::valueFrom($value), $this->precision), $this->precision);
		}

		/**
		 * Returns a new decimal with a value subtracted from it.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function minus($value)
		{
			return new static(bcsub($this->value, static::valueFrom($value), $this->precision), $this->precision);
		}

		/**
		 * Returns a new decimal with the value multiplied by another.
		 * @param mixed $factor
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function times($factor)
		{
			return new static(bcmul($this->value, static::valueFrom($factor), $this->precision), $this->precision);
		}

		/**
		 * Returns a new decimal with the value divided by another.
		 * @param mixed $divisor
		 * @return static
		 * @throws DecimalException
		 */
		public function dividedBy($divisor): self
		{
			return new static(bcdiv($this->value, static::valueFrom($divisor), $this->precision), $this->precision);
		}

		/**
		 * Returns the modulus of the decimal with a divisor.
		 * @param mixed $divisor
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function modulus($divisor)
		{
			return new static(bcmod($this->value, static::valueFrom($divisor), $this->precision), $this->precision);
		}

		/**
		 * Determines if the decimal is equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function equals($value): bool
		{
			$value = static::valueFrom($value, $this->precision);
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
			$value = static::valueFrom($value, $this->precision);
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
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) < 0;
		}

		/**
		 * Determines if the decimal is less than or equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function lessThanOrEqual($value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) <= 0;
		}

		/**
		 * Determines if the decimal is greater than another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function greaterThan($value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) > 0;
		}

		/**
		 * Determines if the decimal is greater than or equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function greaterThanOrEqual($value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) >= 0;
		}

		/**
		 * Returns the smaller value of the two as a new decimal.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function min($value): self
		{
			$value = static::valueFrom($value, $this->precision);
			return new static(bccomp($this->value, $value, $this->precision) < 0 ? $this->value : $value);
		}

		/**
		 * Returns the larger value of the two as a new decimal.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function max($value): self
		{
			$value = static::valueFrom($value, $this->precision);
			return new static(bccomp($this->value, $value, $this->precision) > 0 ? $this->value : $value);
		}

		/**
		 * Returns the decimal rounded to a certain precision.
		 * @param int $precision
		 * @return static
		 */
		public function round(int $precision = 0): self
		{
			$multiplier = bcpow(10, $precision + 1, 0);
			$whole = bcmul($this->value, $multiplier, 0);
			$mod = intval(bcmod($whole, 10));
			$whole = $mod < 5 ?
				bcsub($whole, $mod, 0) :
				bcadd($whole, 10 - $mod, 0);
			return new static(bcdiv($whole, $multiplier, $this->precision), $this->precision);
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
		 */
		public function ceil(): self
		{
			$floor = bcsub($this->value, bcmod($this->value, '1', $this->precision), $this->precision);
			if (bccomp($this->value, $floor, $this->precision) !== 0)
				$floor = bcadd($floor, '1', $this->precision);
			return new static($floor, $this->precision);
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
