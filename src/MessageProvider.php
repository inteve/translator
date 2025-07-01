<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	interface MessageProvider
	{
		/**
		 * @param  array<string, mixed> $parameters
		 * @return Message|NULL
		 */
		function getMessage(
			Locale $locale,
			MessageId $messageId,
			array $parameters
		);
	}
