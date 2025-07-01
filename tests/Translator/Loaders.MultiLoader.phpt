<?php

declare(strict_types=1);

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('getAllMessages()', function () {
	$csLoader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('cs'), [
		'homepage.hello' => 'Ahoj!',
		'homepage.hello2' => 'Ahoj, <b>{$name}</b>!',
		'homepage.hello3' => 'Hoj!',
	]);

	$enLoader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	]);

	$loader = new Translator\Loaders\MultiLoader([
		$csLoader,
		$enLoader,
	]);

	Assert::same([
		'homepage.hello' => 'Ahoj!',
		'homepage.hello2' => 'Ahoj, <b>{$name}</b>!',
		'homepage.hello3' => 'Hoj!',
	],$loader->getAllMessages(Translator\LanguageTag::fromString('cs')));

	Assert::same([
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	],$loader->getAllMessages(Translator\LanguageTag::fromString('en')));

	Assert::same([],$loader->getAllMessages(Translator\LanguageTag::fromString('en-GB')));
});
