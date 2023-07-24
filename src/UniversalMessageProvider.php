<?php

	namespace Inteve\Translator;

	use Nette\Utils\Strings;


	class UniversalMessageProvider implements MessageProvider
	{
		/** @var MessageLoader */
		private $messageLoader;

		/** @var MessageProcessor */
		private $messageProcessor;


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
			$message = $this->messageLoader->getMessage($locale->getLanguageTag(), $messageId);

			if ($message === NULL || $message === '') {
				return NULL;

			} elseif (Strings::startsWith($message, '@') && MessageId::isValid($tmp = Strings::substring($message, 1))) {
				return $this->getMessage(
					$locale,
					new MessageId($tmp),
					$parameters
				);
			}

			$messageText = $this->messageProcessor->processMessage($message);
			return new Message($messageId, $messageText->format($parameters, $locale));
		}
	}
