<?php

	namespace Inteve\Translator;

	use Nette\Utils\Html;


	class HtmlTranslator implements Translator
	{
		/** @var Locale */
		private $locale;

		/** @var MessageProvider */
		private $messageProvider;


		public function __construct(
			Locale $locale,
			MessageProvider $messageProvider
		)
		{
			$this->locale = $locale;
			$this->messageProvider = $messageProvider;
		}


		public function translate($message, array $parameters = [])
		{
			if (is_string($message)) {
				return $this->translateMessage(new MessageId($message), $parameters);

			} elseif ($message instanceof MessageId) {
				return $this->translateMessage($message, $parameters);

			} elseif ($message instanceof Translate) {
				return $this->translateMessage($message->getId(), $parameters + $message->getParameters());

			} elseif ($message instanceof NotTranslate) {
				return $message->getText();
			}

			throw new InvalidArgumentException('Invalid type of message.');
		}


		/**
		 * @param  string $prefix
		 * @return PrefixedTranslator
		 */
		public function prefix($prefix)
		{
			return new PrefixedTranslator($prefix, $this);
		}


		/**
		 * @param  array<string, mixed> $parameters
		 * @return Html|string
		 */
		private function translateMessage(MessageId $messageId, array $parameters)
		{
			$message = $this->messageProvider->getMessage($this->locale, $messageId, $parameters);

			if ($message === NULL) { // missing translate
				return $messageId->toString();
			}

			$res = Html::el();

			foreach ($message->getElements() as $element) {
				if ($element instanceof MessageElement) {
					$this->addElementToHtml($res, $element);

				} else {
					$res->addText($element);
				}
			}

			return $res;
		}


		/**
		 * @return void
		 */
		private function addElementToHtml(Html $target, MessageElement $element)
		{
			if ($element->is('b', 'strong', 'em', 'i', 'sub', 'sup', 'span', 'a')) {
				$target = $target->create($element->getName(), $element->getAttributes());
			}

			foreach ($element->getChildren() as $child) {
				if ($child instanceof MessageElement) {
					$this->addElementToHtml($target, $child);

				} else {
					$target->addText($child);
				}
			}
		}
	}
