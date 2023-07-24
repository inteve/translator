<?php

	namespace Inteve\Translator;


	interface MessageProvider
	{
		/**
		 * @param  array<string, mixed> $parameters
		 * @return Message|NULL
		 */
		function getMessage(
			LanguageTag $languageTag,
			MessageId $messageId,
			array $parameters
		);
	}
