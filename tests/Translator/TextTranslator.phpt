<?php

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @param  string $lang
 * @return Translator\TextTranslator
 */
function createTranslator($lang)
{
	return new Translator\TextTranslator(
		Tests::createLocale($lang),
		new Translator\Providers\UniversalMessageProvider(
			new Translator\Loaders\MultiLoader([
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('cs'), [
					'homepage.hello' => 'Ahoj!',
					'homepage.hello2' => 'Ahoj, <b>{$name}</b>!',
				]),
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'homepage.hello' => 'Hello!',
					'homepage.hello2' => 'Hello, <b>{$name}</b>!',
				]),
			]),
			new Translator\Processors\TagProcessor
		)
	);
}


test('basic', function () {
	$csTranslator = createTranslator('cs');
	Assert::same('Ahoj!', $csTranslator->translate('homepage.hello'));

	$enTranslator = createTranslator('en');
	Assert::same('Hello!', $enTranslator->translate('homepage.hello'));
});


test('with parameters', function () {
	$csTranslator = createTranslator('cs');
	Assert::same('Ahoj, John!', $csTranslator->translate('homepage.hello2', ['name' => 'John']));

	$enTranslator = createTranslator('en');
	Assert::same('Hello, John!', $enTranslator->translate('homepage.hello2', ['name' => 'John']));
});
