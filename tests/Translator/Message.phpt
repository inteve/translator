<?php

declare(strict_types=1);

use Inteve\Translator\Message;
use Inteve\Translator\MessageId;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Basic', function () {
	$messageId = new MessageId('my.message');
	$message = new Message(
		$messageId,
		[
			'text',
		]
	);

	Assert::same($messageId, $message->getId());
	Assert::same([
		'text',
	], $message->getElements());
});
