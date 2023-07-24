<?php

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

function createTranslator($lang)
{
	return new Translator\TextTranslator(
		Translator\LanguageTag::fromString($lang),
		new Translator\UniversalMessageProvider(
			new Translator\Loaders\MultiLoader([
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('cs'), [
					'homepage.hello' => 'Ahoj!',
					'homepage.hello2' => 'Ahoj, {$name}!',
				]),
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'homepage.hello' => 'Hello!',
					'homepage.hello2' => 'Hello, {$name}!',
				]),
			]),
			new Translator\Processors\HtmlProcessor
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
