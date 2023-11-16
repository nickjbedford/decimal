<?php
	use PHPUnit\Framework\TestCase;
	use YetAnother\DecimalException;
	use YetAnother\ImmutableDecimal;
	
	class ImmutableDecimalTests extends TestCase
	{
		/**
		 * @throws DecimalException
		 */
		function testFromMixedWithValidValuesAreCorrect()
		{
			$int = ImmutableDecimal::from(123);
			$float = ImmutableDecimal::from(123.456);
			$bool = ImmutableDecimal::from(true);
			$string = ImmutableDecimal::from('123.456789123');
			
			$this->assertEquals('123.000000', $int->value());
			$this->assertEquals('123.456000', $float->value());
			$this->assertEquals('1.000000', $bool->value());
			$this->assertEquals('123.456789', $string->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testFromObjectThrowsException()
		{
			$this->expectException(DecimalException::class);
			
			ImmutableDecimal::from(new stdClass());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testFromNonNumericStringThrowsException()
		{
			$this->expectException(DecimalException::class);
			
			ImmutableDecimal::from('Hello');
		}
		
		/**
		 * @throws DecimalException
		 */
		function testFromArrayThrowsException()
		{
			$this->expectException(DecimalException::class);
			
			ImmutableDecimal::from([ 123 ]);
		}
		
		/**
		 * @throws DecimalException
		 */
		function testCopyWorks()
		{
			$original = ImmutableDecimal::from('123.456789');
			
			$this->assertEquals('123.456789', $original->copy()->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testPrecisionIsCorrect()
		{
			$original = ImmutableDecimal::from('123.456789');
			
			$this->assertEquals(6, $original->precision());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testChangedPrecisionIsCorrect()
		{
			$original = ImmutableDecimal::from('123.456789');
			
			$precise = $original->toPrecision(3);
			
			$this->assertEquals(3, $precise->precision());
			$this->assertEquals('123.456', $precise->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testPlusComputesCorrectly()
		{
			$original = ImmutableDecimal::from('123.111111');
			$result = $original->plus('321.222222');
			
			$this->assertEquals('444.333333', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testMinusComputesCorrectly()
		{
			$original = ImmutableDecimal::from('123.111111');
			$result = $original->minus('12.101010');
			
			$this->assertEquals('111.010101', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testTimesComputesCorrectly()
		{
			$original = ImmutableDecimal::from('123.111111');
			$result = $original->times('2.5');
			
			$this->assertEquals('307.777777', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testDividedByComputesCorrectly()
		{
			$original = ImmutableDecimal::from('123.111111');
			$result = $original->dividedBy('2.5');
			
			$this->assertEquals('49.244444', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testModulusComputesCorrectly()
		{
			$original = ImmutableDecimal::from('123.111111');
			$result = $original->modulus('2.5');
			
			$this->assertEquals('0.611111', $result->value());
			
			$amount = d4('129.9523')->plus(87.5);
			$remainder = $amount->modulus(5.5);
			
			$this->assertEquals('2.9523', $remainder->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testEqualityFunctionsWorkCorrectly()
		{
			$value = ImmutableDecimal::from('123.111111');
			
			$this->assertTrue($value->equals('123.111111'));
			$this->assertTrue($value->notEquals('123.111112'));
			$this->assertTrue($value->greaterThan('123.111110'));
			$this->assertTrue($value->lessThan('123.111112'));
			$this->assertTrue($value->greaterThanOrEqual('123.111110'));
			$this->assertTrue($value->greaterThanOrEqual('123.111111'));
			$this->assertTrue($value->lessThanOrEqual('123.111112'));
			$this->assertTrue($value->lessThanOrEqual('123.111111'));
		}
		
		/**
		 * @throws DecimalException
		 */
		function testMinMaxFunctionsWorkCorrectly()
		{
			$value = ImmutableDecimal::from('123.111111');
			
			$this->assertEquals('123.111112', $value->max('123.111112')->value());
			$this->assertEquals('123.111111', $value->min('123.111112')->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testClampFunctionsWorkCorrectly()
		{
			$value = ImmutableDecimal::from(100);
			
			$this->assertEquals('123.111112', $value->clamp('123.111112', '124'));
			$this->assertEquals('99.883833', $value->clamp('97', '99.883833'));
			$this->assertEquals('100.000000', $value->clamp('97', '129.883833'));
		}
		
		/**
		 * @throws DecimalException
		 */
		function testRoundWorkCorrectly()
		{
			$value = ImmutableDecimal::from('123.111111');
			$result = $value->round(3);
			
			$this->assertEquals('123.111000', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testFloorWorksCorrectly()
		{
			$value = ImmutableDecimal::from('123.111111');
			$result = $value->floor();
			
			$this->assertEquals('123.000000', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testCeilWorksCorrectly()
		{
			$value = ImmutableDecimal::from('123.111111');
			$result = $value->ceil();
			
			$this->assertEquals('124.000000', $result->value());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testIsIntegerWorksCorrectly()
		{
			$this->assertTrue(ImmutableDecimal::from('123.000')->isInteger());
			$this->assertFalse(ImmutableDecimal::from('123.456')->isInteger());
		}
		
		/**
		 * @throws DecimalException
		 */
		function testDollarsToCentsWorksCorrectly()
		{
			$this->assertEquals('15358', d2('153.58')->dollarsToCents());
			$this->assertEquals('30', d2('0.30')->dollarsToCents());
		}
		
		/**
		 * @throws DecimalException
		 */
		static function formatDataProvider(): array
		{
			return [
				[ '1', d0(1)->format() ],
				[ '123', d0(123)->format() ],
				[ '123,456', d0(123456)->format() ],
				[ '12,345,678', d0(12345678)->format() ],
				
				[ '$1', d0(1)->format(null, '.', ',', '$') ],
				[ '$123', d0(123)->format(null, '.', ',', '$') ],
				[ '$123,456', d0(123456)->format(null, '.', ',', '$') ],
				[ '$12,345,678', d0(12345678)->format(null, '.', ',', '$') ],
				
				[ '-1', d0(-1)->format() ],
				[ '-123', d0(-123)->format() ],
				[ '-123,456', d0(-123456)->format() ],
				[ '-12,345,678', d0(-12345678)->format() ],
				
				[ '-$1', d0(-1)->format(null, '.', ',', '$') ],
				[ '-$123', d0(-123)->format(null, '.', ',', '$') ],
				[ '-$123,456', d0(-123456)->format(null, '.', ',', '$') ],
				[ '-$12,345,678', d0(-12345678)->format(null, '.', ',', '$') ],
				
				[ '1.90', d2(1.9)->format() ],
				[ '123.90', d2(123.9)->format() ],
				[ '123,456.90', d2(123456.9)->format() ],
				[ '12,345,678.90', d2(12345678.9)->format() ],
				
				[ '$1.90', d2(1.9)->format(null, '.', ',', '$') ],
				[ '$123.90', d2(123.9)->format(null, '.', ',', '$') ],
				[ '$123,456.90', d2(123456.9)->format(null, '.', ',', '$') ],
				[ '$12,345,678.90', d2(12345678.9)->format(null, '.', ',', '$') ],
				
				[ '-1.90', d2(-1.9)->format() ],
				[ '-123.90', d2(-123.9)->format() ],
				[ '-123,456.90', d2(-123456.9)->format() ],
				[ '-12,345,678.90', d2(-12345678.9)->format() ],
				
				[ '-$1.90', d2(-1.9)->format(null, '.', ',', '$') ],
				[ '-$123.90', d2(-123.9)->format(null, '.', ',', '$') ],
				[ '-$123,456.90', d2(-123456.9)->format(null, '.', ',', '$') ],
				[ '-$12,345,678.90', d2(-12345678.9)->format(null, '.', ',', '$') ],
				
				[ '-$1,812,933.9472', d4(-1812933.9472)->format(null, '.', ',', '$') ],
			];
		}
		
		/**
		 * @dataProvider formatDataProvider
		 */
		function testFormatCreatesCorrectStrings(string $expected, string $actual)
		{
			$this->assertEquals($expected, $actual);
		}
	}
