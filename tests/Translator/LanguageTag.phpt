<?php

use Inteve\Translator\LanguageTag;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('valid', function () {
	$tag = new LanguageTag('cs-CZ');
	Assert::same('cs-CZ', $tag->toString());
	Assert::same('cs', $tag->getLang());
	Assert::true($tag->isLang('cs'));
	Assert::false($tag->isLang('en'));
	Assert::same('CZ', $tag->getCountry());
	Assert::true($tag->isCountry('CZ'));
	Assert::false($tag->isCountry('US'));

	$tag = new LanguageTag('en-US');
	Assert::same('en-US', $tag->toString());
	Assert::same('en', $tag->getLang());
	Assert::false($tag->isLang('cs'));
	Assert::true($tag->isLang('en'));
	Assert::same('US', $tag->getCountry());
	Assert::false($tag->isCountry('cz'));
	Assert::true($tag->isCountry('us'));
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
