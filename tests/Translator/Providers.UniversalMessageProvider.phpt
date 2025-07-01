<?php

declare(strict_types=1);

use Inteve\Translator\LanguageTag;
use Inteve\Translator\Loaders\ArrayLoader;
use Inteve\Translator\MessageId;
use Inteve\Translator\Processors\TagProcessor;
use Inteve\Translator\Providers\UniversalProvider;
use Inteve\Translator\UniversalLocale;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Missing message', function () {
	$provider = new UniversalProvider(
		new ArrayLoader(LanguageTag::fromString('en'), [
			'message' => 'Message 1',
		]),
		new TagProcessor
	);
	$locale = new UniversalLocale(LanguageTag::fromString('en'));

	$message = $provider->getMessage($locale, new MessageId('message'), []);
	assert($message !== NULL);
	Assert::same(['Message 1'], $message->getElements());

	Assert::null($provider->getMessage($locale, new MessageId('missing.message'), []));
	Assert::null($provider->getMessage($locale, new MessageId('missing.message'), []), 'Missing message loaded from memory cache');
});


test('Message reference', function () {
	$provider = new UniversalProvider(
		new ArrayLoader(LanguageTag::fromString('en'), [
			'page1.title' => 'Message 1',
			'page2.title' => '@page1.title',
			'page3.title' => '@page2.title',
			'page4.title' => '@page3.title',
		]),
		new TagProcessor
	);
	$locale = new UniversalLocale(LanguageTag::fromString('en'));

	$message = $provider->getMessage($locale, new MessageId('page2.title'), []);
	assert($message !== NULL);
	Assert::same(['Message 1'], $message->getElements());

	$message = $provider->getMessage($locale, new MessageId('page4.title'), []);
	assert($message !== NULL);
	Assert::same(['Message 1'], $message->getElements());
});


test('Message cyclic reference', function () {
	$provider = new UniversalProvider(
		new ArrayLoader(LanguageTag::fromString('en'), [
			'page1.title' => '@page4.title',
			'page2.title' => '@page1.title',
			'page3.title' => '@page2.title',
			'page4.title' => '@page3.title',
		]),
		new TagProcessor
	);
	$locale = new UniversalLocale(LanguageTag::fromString('en'));

	Assert::null($provider->getMessage($locale, new MessageId('page4.title'), []));
});
