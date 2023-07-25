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
		new Translator\Providers\UniversalMessageProvider(
			new Translator\Loaders\MultiLoader([
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'homepage.hello' => 'Hello, <b>{$name}</b>!',
					'homepage.unknow' => 'Hello, <muted>lorem <i>{$name|lower}!</i></muted>',
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
