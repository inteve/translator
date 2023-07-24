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
		Translator\LanguageTag::fromString($lang),
		new Translator\UniversalMessageProvider(
			new Translator\Loaders\MultiLoader([
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'homepage.hello' => 'Hello, <b>{$name}</b>!',
					'homepage.unknow' => 'Hello, <muted>lorem <i>{$name}!</i></muted>',
				]),
			]),
			new Translator\Processors\HtmlProcessor
		)
	);
}


test('tag filtering', function () {
	$translator = createTranslator('en');
	Assert::same('Hello, <b>John</b>!', (string) $translator->translate('homepage.hello', ['name' => 'John']));
	Assert::same('Hello, lorem <i>John!</i>', (string) $translator->translate('homepage.unknow', ['name' => 'John']));
});
