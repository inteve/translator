<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	interface MessageLoader
	{
		/**
		 * @return string|NULL
		 */
		function getMessage(LanguageTag $languageTag, MessageId $messageId);


		/**
		 * @return array<string, string>
		 */
		function getAllMessages(LanguageTag $languageTag);
	}
