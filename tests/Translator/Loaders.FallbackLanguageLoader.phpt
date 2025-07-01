<?php

declare(strict_types=1);

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('getMessage()', function () {
	$csLoader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('cs'), [
		'homepage.hello' => 'Ahoj!',
	]);

	$enLoader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	]);

	$multiLoader = new Translator\Loaders\MultiLoader([
		$csLoader,
		$enLoader,
	]);

	$loader = new Translator\Loaders\FallbackLanguageLoader(
		$multiLoader,
		Translator\LanguageTag::fromString('en')
	);

	Assert::same('Ahoj!', $loader->getMessage(Translator\LanguageTag::fromString('cs'), new Translator\MessageId('homepage.hello')));

	Assert::same('Hello, <b>{$name}</b>!', $loader->getMessage(Translator\LanguageTag::fromString('cs'), new Translator\MessageId('homepage.hello2')));
});


test('getAllMessages()', function () {
	$csLoader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('cs'), [
		'homepage.hello' => 'Ahoj!',
	]);

	$enLoader = new Translator\Loaders\ArrayLoader(Translator\LanguageTag::fromString('en'), [
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	]);

	$multiLoader = new Translator\Loaders\MultiLoader([
		$csLoader,
		$enLoader,
	]);

	$loader = new Translator\Loaders\FallbackLanguageLoader(
		$multiLoader,
		Translator\LanguageTag::fromString('en')
	);

	Assert::same([
		'homepage.hello' => 'Ahoj!',
	],$loader->getAllMessages(Translator\LanguageTag::fromString('cs')));

	Assert::same([
		'homepage.hello' => 'Hello!',
		'homepage.hello2' => 'Hello, <b>{$name}</b>!',
	],$loader->getAllMessages(Translator\LanguageTag::fromString('en')));

	Assert::same([],$loader->getAllMessages(Translator\LanguageTag::fromString('en-GB')));
});
