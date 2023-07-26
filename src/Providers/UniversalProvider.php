<?php

	namespace Inteve\Translator\Providers;

	use Inteve\Translator\Ast;
	use Inteve\Translator\Locale;
	use Inteve\Translator\Message;
	use Inteve\Translator\MessageId;
	use Inteve\Translator\MessageLoader;
	use Inteve\Translator\MessageProcessor;
	use Inteve\Translator\MessageProvider;
	use Nette\Utils\Strings;


	class UniversalProvider implements MessageProvider
	{
		/** @var MessageLoader */
		private $messageLoader;

		/** @var MessageProcessor */
		private $messageProcessor;

		/** @var array<string, array<string, Ast\MessageText|NULL>> */
		private $messagesCache = [];


		/**
		 * @param MessageLoader $messageLoader
		 * @param MessageProcessor $messageProcessor
		 */
		public function __construct(
			MessageLoader $messageLoader,
			MessageProcessor $messageProcessor
		)
		{
			$this->messageLoader = $messageLoader;
			$this->messageProcessor = $messageProcessor;
		}


		public function getMessage(
			Locale $locale,
			MessageId $messageId,
			array $parameters
		)
		{
			$languageTag = $locale->getLanguageTag();
			$langTagKey = $languageTag->toString();
			$messageIdKey = $messageId->toString();

			if (!isset($this->messagesCache[$langTagKey][$messageIdKey])) {
				if (!isset($this->messagesCache[$langTagKey])) {
					$this->messagesCache[$langTagKey] = [];
				}

				if (array_key_exists($messageIdKey, $this->messagesCache[$langTagKey])) { // missing
					return NULL;
				}

				$message = $this->messageLoader->getMessage($languageTag, $messageId);

				if ($message === NULL || $message === '') {
					return $this->messagesCache[$langTagKey][$messageIdKey] = NULL;

				} elseif (Strings::startsWith($message, '@') && MessageId::isValid($tmp = Strings::substring($message, 1))) {
					$message = $this->getMessage(
						$locale,
						new MessageId($tmp),
						$parameters
					);

					if (isset($this->messagesCache[$langTagKey][$tmp])) {
						$this->messagesCache[$langTagKey][$messageIdKey] = $this->messagesCache[$langTagKey][$tmp];
					}

					return $message;
				}

				$this->messagesCache[$langTagKey][$messageIdKey] = $this->messageProcessor->processMessage($message);
			}

			return new Message($messageId, $this->messagesCache[$langTagKey][$messageIdKey]->format($parameters, $locale));
		}
	}
