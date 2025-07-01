<?php

declare(strict_types=1);

use Inteve\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Get all messages', function () {
	$loader = new Translator\Loaders\NeonLoader(NULL, __DIR__ . '/fixtures/langs');

	Assert::same([
		'admin.page.header' => 'Administrace',
		'rootDomainMessage' => 'cs',
		'shopOrder.page.header' => 'Eshop / Objednávky',
	], $loader->getAllMessages(Translator\LanguageTag::fromString('cs')));

	Assert::same([
		'admin.page.header' => 'Administration',
		'rootDomainMessage' => 'en-GB',
		'shopOrder.page.header' => 'Eshop / Orders',
	], $loader->getAllMessages(Translator\LanguageTag::fromString('en-GB')));

	Assert::same([],$loader->getAllMessages(Translator\LanguageTag::fromString('de')));

});


test('Get message', function () {
	$loader = new Translator\Loaders\NeonLoader(NULL, __DIR__ . '/fixtures/langs');
	$messageId = new Translator\MessageId('rootDomainMessage');
	$nonExistsId = new Translator\MessageId('nonExists');

	$languageTag = Translator\LanguageTag::fromString('cs');
	Assert::same('cs', $loader->getMessage($languageTag, $messageId));
	Assert::null($loader->getMessage($languageTag, $nonExistsId));
	Assert::null($loader->getMessage($languageTag, $nonExistsId), 'Missing message loaded from memory cache');

	$languageTag = Translator\LanguageTag::fromString('en-GB');
	Assert::same('en-GB', $loader->getMessage($languageTag, $messageId));
	Assert::null($loader->getMessage($languageTag, $nonExistsId));
});


test('Prefixed', function () {
	$loader = new Translator\Loaders\NeonLoader('my.appModule', __DIR__ . '/fixtures/langs');
	$languageTag = Translator\LanguageTag::fromString('cs');

	Assert::same([
		'my.appModule.admin.page.header' => 'Administrace',
		'my.appModule.rootDomainMessage' => 'cs',
		'my.appModule.shopOrder.page.header' => 'Eshop / Objednávky',
	], $loader->getAllMessages($languageTag));

	Assert::same('cs', $loader->getMessage($languageTag, new Translator\MessageId('my.appModule.rootDomainMessage')));

	Assert::null($loader->getMessage($languageTag, new Translator\MessageId('my.appModule2.rootDomainMessage')));

});
