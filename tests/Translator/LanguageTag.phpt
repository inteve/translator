<?php

use Inteve\Translator\LanguageTag;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('valid', function () {
	$tag = new LanguageTag('cs-CZ');
	Assert::same('cs-CZ', $tag->toString());
	Assert::true($tag->isLang('cs'));
	Assert::false($tag->isLang('en'));

	$tag = new LanguageTag('en-US');
	Assert::same('en-US', $tag->toString());
	Assert::false($tag->isLang('cs'));
	Assert::true($tag->isLang('en'));
});


test('invalid', function () {
	Assert::exception(function () {
		new LanguageTag("en-US\n");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("EN-US");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("ENUS");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("en");
	}, \CzProject\Assert\AssertException::class);

	Assert::exception(function () {
		new LanguageTag("en-09");
	}, \CzProject\Assert\AssertException::class);
});


test('fromString', function () {
	Assert::same('cs-CZ', LanguageTag::fromString('cs')->toString());
	Assert::same('en-US', LanguageTag::fromString('en')->toString());

	Assert::same('cs-CZ', LanguageTag::fromString('cs_CZ')->toString());
	Assert::same('en-US', LanguageTag::fromString('en_US')->toString());
});
