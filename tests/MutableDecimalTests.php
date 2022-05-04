<?php
	use PHPUnit\Framework\TestCase;
	use YetAnother\Decimal;
	use YetAnother\DecimalException;
	
	class MutableDecimalTests extends TestCase
	{
		/**
		 * @throws DecimalException
		 */
		function testAddComputesCorrectly()
		{
			$value = Decimal::from('123.456');
			
			$this->assertInstanceOf(Decimal::class, $value);
			
			$value->add('321.432');
			
			$this->assertEquals('444.888000', $value->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testSubComputesCorrectly()
		{
			$value = Decimal::from('123.456');
			$value->sub('321.432');
			
			$this->assertEquals('-197.976000', $value->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testMulComputesCorrectly()
		{
			$value = Decimal::from('123.456');
			$value->mul('321.432');
			
			$this->assertEquals('39682.708992', $value->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testDivComputesCorrectly()
		{
			$value = Decimal::from('123.456');
			$value->div('321.432');
			
			$this->assertEquals('0.384081', $value->value());
		}
	}
