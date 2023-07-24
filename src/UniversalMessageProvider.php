<?php

	namespace Inteve\Translator;

	use Nette\Utils\Strings;


	class UniversalMessageProvider implements MessageProvider
	{
		/** @var MessageLoader */
		private $messageLoader;

		/** @var MessageProcessor */
		private $messageProcessor;


		public function __construct(
			MessageLoader $messageLoader,
			MessageProcessor $messageProcessor
		)
		{
			$this->messageLoader = $messageLoader;
			$this->messageProcessor = $messageProcessor;
		}


		public function getMessage(
			LanguageTag $languageTag,
			MessageId $messageId,
			array $parameters
		)
		{
			$messageText = $this->messageLoader->getMessage($languageTag, $messageId);

			if ($messageText === NULL) {
				return $messageText;

			} elseif (Strings::startsWith($messageText, '@') && MessageId::isValid($tmp = Strings::substring($messageText, 1))) {
				return $this->getMessage(
					$languageTag,
					new MessageId($tmp),
					$parameters
				);
			}

			$messageElements = $this->messageProcessor->processMessage($messageText);
			return new Message($messageId, $this->replaceParameters($messageElements, $parameters));
		}


		/**
		 * @param  array<string|MessageElement|Parameter> $messageElements
		 * @param  array<string, mixed> $parameters
		 * @return array<string|MessageElement>
		 */
		private function replaceParameters(array $messageElements, array $parameters)
		{
			$res = [];

			foreach ($messageElements as $messageElement) {
				if ($messageElement instanceof Parameter) {
					$paramName = $messageElement->getName();

					if (isset($parameters[$paramName])) {
						$res[] = $messageElement->toString($parameters[$paramName]);
					}

				} else {
					$res[] = $messageElement;
				}
			}

			return $res;
		}
	}
