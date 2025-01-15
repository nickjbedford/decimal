<?php
	use PHPUnit\Framework\TestCase;
	use YetAnother\DecimalException;
	use YetAnother\ImmutableDecimal;
	
	class SerializationTests extends TestCase
	{
		/**
		 * @throws DecimalException
		 */
		function testJsonSerializeDeserializeWorks()
		{
			$int = ImmutableDecimal::from(123);
			$float = ImmutableDecimal::from(123.456);
			$bool = ImmutableDecimal::from(true);
			$string = ImmutableDecimal::from('123.456789123', 9);
			$invalid = ImmutableDecimal::fromNoThrow('ad', 2);

			$json = json_encode([
				$int,
				$float,
				$bool,
				$string,
				$invalid
			]);
			
			$this->assertEquals(json_encode([
				[
					'value' => '123.000000',
					'precision' => 6
				],
				[
					'value' => '123.456000',
					'precision' => 6
				],
				[
					'value' => '1.000000',
					'precision' => 6
				],
				[
					'value' => '123.456789123',
					'precision' => 9
				],
				[
					'value' => '0.00',
					'precision' => 2
				]
			]), $json);
			
			$strings = [
				'123.000000',
				'123.456000',
				'1.000000',
				'123.456789123',
				'0.00'
			];
			
			$deserializedObjects = array_map(fn(object $item) => ImmutableDecimal::fromJson($item), json_decode($json));
			$deserializedArrays = array_map(fn(array $item) => ImmutableDecimal::fromJson($item), json_decode($json, true));
			
			foreach($strings as $i=>$value)
			{
				$this->assertEquals($value, $deserializedObjects[$i]->value());
				$this->assertEquals($value, $deserializedArrays[$i]->value());
			}
			
			$deserializedStrings = array_map(fn(string $item) => ImmutableDecimal::fromJson($item), $strings);
			
			foreach([
				'123.000000',
				'123.456000',
				'1.000000',
				'123.456789',
				'0.000000'
			] as $i=>$value)
			{
				$this->assertEquals($value, $deserializedStrings[$i]->value());
			}
		}
	}
