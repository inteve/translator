<?php

use Inteve\Translator\LanguageTag;
use Inteve\Translator\UniversalLocale;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Date formatting', function () {
	$locale = new UniversalLocale(
		LanguageTag::fromString('en'),
		'j.n.Y / H:i:s',
		'Y/m/d',
		'G:i'
	);
	$date = new \DateTimeImmutable('2023-01-02 03:04:05', new \DateTimeZone('UTC'));

	Assert::same('2.1.2023 / 03:04:05', $locale->formatValue($date, [])); // autoconvert
	Assert::same('2.1.2023 / 03:04:05', $locale->formatValue($date, ['datetime']));
	Assert::same('2023/01/02', $locale->formatValue($date, ['date']));
	Assert::same('3:04', $locale->formatValue($date, ['time']));

	Assert::same('invalid', $locale->formatValue('invalid', ['datetime'])); // invalid value
	Assert::same('invalid', $locale->formatValue('invalid', ['date'])); // invalid value
	Assert::same('invalid', $locale->formatValue('invalid', ['time'])); // invalid value
});


test('String formatting', function () {
	$locale = new UniversalLocale(
		LanguageTag::fromString('en'),
		'j.n.Y / H:i:s',
		'Y/m/d',
		'G:i'
	);

	Assert::same('Capitalized String', $locale->formatValue('Capitalized String', [])); // no conversion
	Assert::same('capitalized string', $locale->formatValue('Capitalized String', ['lower']));
	Assert::same('CAPITALIZED STRING', $locale->formatValue('Capitalized String', ['upper']));
	Assert::same('Lower string', $locale->formatValue('lower string', ['firstUpper']));

	$invalid = new \stdClass;
	Assert::same('', $locale->formatValue($invalid, ['lower']));
});
