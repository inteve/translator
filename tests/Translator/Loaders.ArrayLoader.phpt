<?php

declare(strict_types=1);

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('getAllMessages()', function () {
	$loader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	]);

	Assert::same([
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	],$loader->getAllMessages(Translator\LanguageTag::fromString('en')));

	Assert::same([],$loader->getAllMessages(Translator\LanguageTag::fromString('cs')));
});
