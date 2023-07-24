<?php

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
