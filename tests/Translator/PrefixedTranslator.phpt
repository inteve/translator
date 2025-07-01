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
				new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
					'pages.homepage.hello' => 'Hello!',
				]),
			]),
			new Translator\Processors\TagProcessor
		)
	);
}


test('basic', function () {
	$translator = createTranslator('en')
		->prefix('pages')
		->prefix('homepage');
	Assert::same('Hello!', $translator->translate('hello'));
});
