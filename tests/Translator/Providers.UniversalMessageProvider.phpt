<?php

use Inteve\Translator\LanguageTag;
use Inteve\Translator\Loaders\ArrayLoader;
use Inteve\Translator\MessageId;
use Inteve\Translator\Processors\TagProcessor;
use Inteve\Translator\Providers\UniversalMessageProvider;
use Inteve\Translator\UniversalLocale;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Missing message', function () {
	$provider = new UniversalMessageProvider(
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
