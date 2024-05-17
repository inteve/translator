<?php

	namespace Inteve\Translator\Loaders;

	use Inteve\Translator\LanguageTag;
	use Inteve\Translator\MessageId;
	use Inteve\Translator\MessageLoader;


	class FallbackLanguageLoader implements MessageLoader
	{
		/** @var MessageLoader */
		private $messageLoader;

		/** @var LanguageTag */
		private $languageTag;


		public function __construct(
			MessageLoader $messageLoader,
			LanguageTag $languageTag
		)
		{
			$this->messageLoader = $messageLoader;
			$this->languageTag = $languageTag;
		}


		public function getMessage(LanguageTag $languageTag, MessageId $messageId)
		{
			$message = $this->messageLoader->getMessage($languageTag, $messageId);

			if ($message === NULL) {
				$message = $this->messageLoader->getMessage($this->languageTag, $messageId);
			}

			return $message;
		}


		public function getAllMessages(LanguageTag $languageTag)
		{
			return $this->messageLoader->getAllMessages($languageTag);
		}
	}
