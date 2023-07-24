<?php

	namespace Inteve\Translator\Loaders;

	use Inteve\Translator\LanguageTag;
	use Inteve\Translator\MessageId;


	class ArrayLoader implements \Inteve\Translator\MessageLoader
	{
		/** @var LanguageTag */
		private $languageTag;

		/** @var array<string, string> */
		private $messages;


		/**
		 * @param array<string, string> $messages
		 */
		public function __construct(LanguageTag $languageTag, array $messages)
		{
			$this->languageTag = $languageTag;
			$this->messages = $messages;
		}


		public function getMessage(LanguageTag $languageTag, MessageId $messageId)
		{
			if ($languageTag->equals($this->languageTag) && isset($this->messages[$key = $messageId->toString()])) {
				return $this->messages[$key];
			}

			return NULL;
		}


		public function getAllMessages(LanguageTag $languageTag)
		{
			if ($languageTag->equals($this->languageTag)) {
				return $this->messages;
			}

			return [];
		}
	}
