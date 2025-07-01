<?php

declare(strict_types=1);

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
		new Translator\Providers\UniversalProvider(
			new Translator\Loaders\MultiLoader([
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('cs'), [
					'homepage.hello' => 'Ahoj!',
					'homepage.hello2' => 'Ahoj, <b>{$name}</b>!',
				]),
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'homepage.hello' => 'Hello!',
					'homepage.hello2' => 'Hello, <b>{$name}</b>!',
					'homepage.br' => 'Hello<br>friends',
					'homepage.entity' => 'A&nbsp;entity',
					'homepage.ul' => '<ul><li>A</li><li>B</li></ul>',
					'homepage.ol' => '<ol><li>A</li><li>B</li></ol>',
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


test('empty tags', function () {
	$translator = createTranslator('en');
	Assert::same("Hello\nfriends", (string) $translator->translate('homepage.br'));
});


test('NoTranslate', function () {
	$translator = createTranslator('en');
	Assert::same('homepage.hello', $translator->translate(new Translator\NotTranslate('homepage.hello'), ['name' => 'John']));
	Assert::same('Lorem Ipsum dolor sit amet', $translator->translate(new Translator\NotTranslate('Lorem Ipsum dolor sit amet'), ['name' => 'John']));
});


test('Translate', function () {
	$translator = createTranslator('en');
	Assert::same('Hello, John!', $translator->translate(new Translator\Translate('homepage.hello2'), ['name' => 'John']));
	Assert::same('Hello, John!', $translator->translate(new Translator\Translate('homepage.hello2', ['name' => 'Jack']), ['name' => 'John']));
	Assert::same('Hello, Jack!', $translator->translate(new Translator\Translate('homepage.hello2', ['name' => 'Jack'])));
});


test('MessageId', function () {
	$translator = createTranslator('en');
	Assert::same('Hello, John!', $translator->translate(new Translator\MessageId('homepage.hello2'), ['name' => 'John']));
});


test('Lists', function () {
	$translator = createTranslator('en');
	Assert::same("- A\n- B\n", (string) $translator->translate('homepage.ul'));
	Assert::same("1. A\n2. B\n", (string) $translator->translate('homepage.ol'));
});


test('Entities', function () {
	$translator = createTranslator('en');
	Assert::same("A\xC2\xA0entity", (string) $translator->translate('homepage.entity'));
});


test('Prefix', function () {
	$translator = createTranslator('en')->prefix('homepage');
	Assert::same('Hello, John!', $translator->translate('hello2', ['name' => 'John']));
});


test('Missing translation', function () {
	$translator = createTranslator('en');
	Assert::same('missing.message', $translator->translate('missing.message'));
});
