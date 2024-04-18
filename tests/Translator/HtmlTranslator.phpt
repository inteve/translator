<?php

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @param  string $lang
 * @return Translator\HtmlTranslator
 */
function createTranslator($lang)
{
	return new Translator\HtmlTranslator(
		Tests::createLocale($lang),
		new Translator\Providers\UniversalProvider(
			new Translator\Loaders\MultiLoader([
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'homepage.hello' => 'Hello, <b>{$name}</b>!',
					'homepage.unknow' => 'Hello, <muted>lorem <i>{$name|lower}!</i></muted>',
					'homepage.br' => 'Hello<br>friends',
					'homepage.entity' => 'A&nbsp;entity',
				]),
			]),
			new Translator\Processors\TagProcessor
		)
	);
}


test('tag filtering', function () {
	$translator = createTranslator('en');
	Assert::same('Hello, <b>John</b>!', (string) $translator->translate('homepage.hello', ['name' => 'John']));
	Assert::same('Hello, lorem <i>john!</i>', (string) $translator->translate('homepage.unknow', ['name' => 'John']));
});


test('empty tags', function () {
	$translator = createTranslator('en');
	Assert::same('Hello<br>friends', (string) $translator->translate('homepage.br'));
});


test('NoTranslate', function () {
	$translator = createTranslator('en');
	Assert::same('homepage.hello', (string) $translator->translate(new Translator\NotTranslate('homepage.hello'), ['name' => 'John']));
	Assert::same('Lorem Ipsum dolor sit amet', (string) $translator->translate(new Translator\NotTranslate('Lorem Ipsum dolor sit amet'), ['name' => 'John']));
});


test('Translate', function () {
	$translator = createTranslator('en');
	Assert::same('Hello, <b>John</b>!', (string) $translator->translate(new Translator\Translate('homepage.hello'), ['name' => 'John']));
	Assert::same('Hello, <b>John</b>!', (string) $translator->translate(new Translator\Translate('homepage.hello', ['name' => 'Jack']), ['name' => 'John']));
	Assert::same('Hello, <b>Jack</b>!', (string) $translator->translate(new Translator\Translate('homepage.hello', ['name' => 'Jack'])));
});


test('MessageId', function () {
	$translator = createTranslator('en');
	Assert::same('Hello, <b>John</b>!', (string) $translator->translate(new Translator\MessageId('homepage.hello'), ['name' => 'John']));
});


test('Entities', function () {
	$translator = createTranslator('en');
	Assert::same("A\xC2\xA0entity", (string) $translator->translate('homepage.entity'));
});


test('Prefix', function () {
	$translator = createTranslator('en')->prefix('homepage');
	Assert::same('Hello, <b>John</b>!', (string) $translator->translate('hello', ['name' => 'John']));
});


test('Missing translation', function () {
	$translator = createTranslator('en');
	Assert::same('missing.message', $translator->translate('missing.message'));
});
