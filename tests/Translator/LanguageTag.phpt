<?php

use Inteve\Translator\LanguageTag;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('valid', function () {
	$tag = new LanguageTag('cs_CZ');
	Assert::same('cs_CZ', $tag->toString());

	$tag = new LanguageTag('en_US');
	Assert::same('en_US', $tag->toString());
});


test('invalid', function () {
	Assert::exception(function () {
		new LanguageTag("en_US\n");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("EN_US");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("ENUS");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("en");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("en_09");
	}, \CzProject\Assert\AssertException::class);
});


test('fromString', function () {
	Assert::same('cs_CZ', LanguageTag::fromString('cs')->toString());
	Assert::same('en_US', LanguageTag::fromString('en')->toString());

	Assert::same('cs_CZ', LanguageTag::fromString('cs_CZ')->toString());
	Assert::same('en_US', LanguageTag::fromString('en_US')->toString());
});
