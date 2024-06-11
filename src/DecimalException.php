<?php
	namespace YetAnother;

	use Exception;
	use Throwable;

	/**
	 * Represents an exception thrown in the Decimal class.
	 */
	class DecimalException extends Exception
	{
		/**
		 * The value that caused the exception.
		 * @var mixed
		 */
		public mixed $value;
		
		/**
		 * @inheritDoc
		 */
		public function __construct($message = "", $code = 0, Throwable $previous = null, mixed $value = null)
		{
			parent::__construct($message, $code, $previous);
			$this->value = $value;
		}
	}
