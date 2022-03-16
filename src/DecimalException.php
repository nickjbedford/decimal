<?php
	namespace YetAnother;

	use Exception;
	use Throwable;

	/**
	 * Represents an exception thrown in the Decimal class.
	 * @package App\Library
	 */
	class DecimalException extends Exception
	{
		/**
		 * @inheritDoc
		 */
		public function __construct($message = "", $code = 0, Throwable $previous = null)
		{
			parent::__construct($message, $code, $previous);
		}
	}
