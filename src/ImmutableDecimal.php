<?php /** @noinspection PhpMissingReturnTypeInspection */
	
	/** @noinspection PhpReturnDocTypeMismatchInspection */
	
	namespace YetAnother;

	use Throwable;
	
	/**
	 * Represents an immutable decimal value, defaulting to a high precision of six decimal places.
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
		public static function from(mixed $mixed, ?int $precision = null)
		{
			$precision = $precision ?? self::DefaultPrecision;
			if ($mixed instanceof ImmutableDecimal)
				return new static($mixed->value, $precision ?? $mixed->precision);
			return new static(static::valueFrom($mixed, $precision), $precision);
		}

		/**
		 * Converts a mixed input value a decimal string value usable in the BC math library.
		 * @param mixed $mixed
		 * @param int|null $precision
		 * @return self
		 * @throws DecimalException
		 */
		public static function valueFrom(mixed $mixed, ?int $precision = null): string
		{
			if ($mixed instanceof ImmutableDecimal)
				$mixed = $mixed->value;
			try
			{
				return bcadd(strval($mixed) ?? '0', '0', $precision ?? self::DefaultPrecision);
			}
			catch(Throwable $exception)
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
		 * Returns a similar decimal instance with a new value.
		 * @param mixed $value
		 * @return static
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		private function similar(mixed $value)
		{
			return new static($value, $this->precision);
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
		 * @return static
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
		 * @return static
		 */
		public function toPrecision(int $precision)
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
		public function plus(mixed $value)
		{
			return $this->similar(bcadd($this->value, static::valueFrom($value), $this->precision));
		}

		/**
		 * Returns a new decimal with a value subtracted from it.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function minus(mixed $value)
		{
			return $this->similar(bcsub($this->value, static::valueFrom($value), $this->precision));
		}

		/**
		 * Returns a new decimal with the value multiplied by another.
		 * @param mixed $factor
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function times(mixed $factor)
		{
			return $this->similar(bcmul($this->value, static::valueFrom($factor), $this->precision));
		}

		/**
		 * Returns a new decimal with the value divided by another.
		 * @param mixed $divisor
		 * @return static
		 * @throws DecimalException
		 */
		public function dividedBy(mixed $divisor)
		{
			return $this->similar(bcdiv($this->value, static::valueFrom($divisor), $this->precision));
		}

		/**
		 * Returns a new decimal with the value divided by another.
		 * @param mixed $divisor
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpUnused
		 */
		public function over(mixed $divisor)
		{
			return $this->dividedBy($divisor);
		}

		/**
		 * Returns the modulus of the decimal with a divisor.
		 * @param mixed $divisor
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 */
		public function modulus(mixed $divisor)
		{
			return $this->similar(bcmod($this->value, static::valueFrom($divisor), $this->precision));
		}

		/**
		 * Returns the modulus of the decimal with a divisor.
		 * @param mixed $divisor
		 * @return static
		 * @throws DecimalException
		 * @noinspection PhpMissingReturnTypeInspection
		 * @noinspection PhpUnused
		 */
		public function mod(mixed $divisor)
		{
			return $this->modulus($divisor);
		}

		/**
		 * Determines if the decimal is equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 * @noinspection PhpUnused
		 */
		public function equalTo(mixed $value): bool
		{
			return $this->equals($value);
		}

		/**
		 * Determines if the decimal is equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function equals(mixed $value): bool
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
		public function notEquals(mixed $value): bool
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
		public function lessThan(mixed $value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) < 0;
		}

		/**
		 * Determines if the decimal is less than or equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function lessThanOrEqual(mixed $value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) <= 0;
		}

		/**
		 * Determines if the decimal is greater than another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function greaterThan(mixed $value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) > 0;
		}

		/**
		 * Determines if the decimal is greater than or equal to another value.
		 * @param mixed $value
		 * @return bool
		 * @throws DecimalException
		 */
		public function greaterThanOrEqual(mixed $value): bool
		{
			return bccomp($this->value, static::valueFrom($value, $this->precision), $this->precision) >= 0;
		}

		/**
		 * Returns the smaller value of the two as a new decimal.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function min(mixed $value)
		{
			$value = static::valueFrom($value, $this->precision);
			return $this->similar(bccomp($this->value, $value, $this->precision) < 0 ? $this->value : $value);
		}

		/**
		 * Returns the larger value of the two as a new decimal.
		 * @param mixed $value
		 * @return static
		 * @throws DecimalException
		 */
		public function max(mixed $value)
		{
			$value = static::valueFrom($value, $this->precision);
			return $this->similar(bccomp($this->value, $value, $this->precision) > 0 ? $this->value : $value);
		}
		
		/**
		 * Clamps the value between a minimum and maximum.
		 * @throws DecimalException
		 */
		public function clamp($min, $max)
		{
			return $this->max($min)->min($max);
		}

		/**
		 * Returns the decimal rounded to a certain precision.
		 * @param int $precision
		 * @return static
		 */
		public function round(int $precision = 0)
		{
			$multiplier = bcpow(10, $precision + 1, 0);
			$whole = bcmul($this->value, $multiplier, 0);
			$mod = intval(bcmod($whole, 10));
			$whole = $mod < 5 ?
				bcsub($whole, $mod, 0) :
				bcadd($whole, 10 - $mod, 0);
			return $this->similar(bcdiv($whole, $multiplier, $this->precision));
		}

		/**
		 * Returns the decimal, rounded down to the nearest whole number.
		 * @return static
		 */
		public function floor()
		{
			return $this->similar(bcsub($this->value, bcmod($this->value, '1', $this->precision), $this->precision));
		}

		/**
		 * Returns the decimal, rounded up to the nearest whole number.
		 * @return static
		 */
		public function ceil()
		{
			$floor = bcsub($this->value, bcmod($this->value, '1', $this->precision), $this->precision);
			if (bccomp($this->value, $floor, $this->precision) !== 0)
				$floor = bcadd($floor, '1', $this->precision);
			return $this->similar($floor);
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
		
		/**
		 * Converts the decimal to an integer.
		 * @return int
		 */
		public function toInteger(): int
		{
			return intval($this->value);
		}
		
		/**
		 * Converts the decimal to an floating-point value.
		 * @return float
		 */
		public function toFloat(): float
		{
			return floatval($this->value);
		}
		
		/**
		 * Assuming the decimal represents dollars and cents in fractional form,
		 * this converts it to whole cents. For example, the decimal "128.49" is returned
		 * as a string of "12849".
		 * @return string
		 * @throws DecimalException
		 */
		public function dollarsToCents(): string
		{
			return $this->times(100)->value(0);
		}
		
		/**
		 * Assuming the decimal represents cents in whole form (100 cents = 1.00 dollars),
		 * this converts it to fractional dollars. For example, the decimal 12849 cents
		 * is returned as a string of "128.49".
		 * @return string
		 * @throws DecimalException
		 */
		public function centsToDollars(): string
		{
			return $this->over(100)->value(2);
		}
		
		/**
		 * Formats a decimal number with grouped thousands.
		 * @param int|null $precision The precision of the resulting decimal number.
		 * @param string $decimalPoint The character to use for the decimal point.
		 * @param string $thousandsSeparator The character to use for the thousands separator.
		 * @param string $currencySymbol The currency symbol to use after the sign character.
		 * @return string The format decimal number.
		 * @throws DecimalException
		 */
		public function format(
			?int $precision = null,
			string $decimalPoint = '.',
			string $thousandsSeparator = ',',
			string $currencySymbol = '',
			string $positiveFormat = '{currency}{value}',
			string $negativeFormat = '-{currency}{value}',
			string $zeroFormat = '{currency}{value}'): string
		{
			$precision ??= $this->precision;
			$value = $this->toPrecision($precision);
			$isZero = $value->equals(0);
			$number = $value->value($precision);
			
			// remove negative sign for later
			if ($negative = $value->lessThan(0))
				$number = substr($number, 1);
			
			// get integer component
			$whole = bcadd($number, 0);
			
			// get fraction component
			$fraction = explode('.', bcsub($number, $whole, $precision));
			$fraction = end($fraction);
			
			// generate formatted amount
			$integer = strrev(implode($thousandsSeparator, str_split(strrev($whole), 3)));
			$fraction = $precision > 0 ? ($decimalPoint . $fraction) : '';
			$number = $integer . $fraction;
			$format = $negative ? $negativeFormat : ($isZero ? $zeroFormat : $positiveFormat);
			
			return str_replace(['{currency}', '{value}'], [$currencySymbol, $number], $format);
		}
	}
