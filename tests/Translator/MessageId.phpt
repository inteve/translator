<?php

use Inteve\Translator\Domain;
use Inteve\Translator\MessageId;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('simple', function () {
	$id = new MessageId('message');
	Assert::same('message', $id->toString());

	Assert::true($id->isUnder(''));
	Assert::false($id->isUnder('message'));
	Assert::false($id->isUnder('domain'));
	Assert::false($id->isUnder('domain.subdomain'));
	Assert::false($id->isUnder('domain2'));

	Assert::null($id->getDomain());
});


test('nested', function () {
	$id = new MessageId('domain.subdomain.message');

	Assert::true($id->isUnder(''));
	Assert::false($id->isUnder('message'));
	Assert::true($id->isUnder('domain'));
	Assert::true($id->isUnder('domain.subdomain'));
	Assert::false($id->isUnder('domain2'));

	Assert::equal(new Domain('domain'), $id->getDomain());
	Assert::equal(new Domain('subdomain'), $id->getDomain('domain'));
	Assert::null($id->getDomain('domain.subdomain'));
});


test('Invalid getDomain', function () {

	Assert::exception(function () {
		$id = new MessageId('domain.subdomain.message');
		$id->getDomain('domain2');
	}, \Inteve\Translator\InvalidArgumentException::class, "MessageID 'domain.subdomain.message' has not prefix 'domain2'.");
});
