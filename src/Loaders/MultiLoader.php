<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Loaders;

	use Inteve\Translator\MessageLoader;
	use Inteve\Translator\LanguageTag;
	use Inteve\Translator\MessageId;


	class MultiLoader implements MessageLoader
	{
		/** @var MessageLoader[] */
		private $loaders;


		/**
		 * @param MessageLoader[] $loaders
		 */
		public function __construct(array $loaders)
		{
			$this->loaders = $loaders;
		}


		public function getMessage(LanguageTag $languageTag, MessageId $messageId)
		{
			foreach ($this->loaders as $loader) {
				$messageText = $loader->getMessage($languageTag, $messageId);

				if ($messageText !== NULL) {
					return $messageText;
				}
			}

			return NULL;
		}


		public function getAllMessages(LanguageTag $languageTag)
		{
			$catalog = [];

			foreach ($this->loaders as $loader) {
				$loaderMessages = $loader->getAllMessages($languageTag);
				$catalog = array_merge($loaderMessages, $catalog);
			}

			return $catalog;
		}
	}
