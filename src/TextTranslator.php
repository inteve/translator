<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	class TextTranslator implements Translator
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
		 * @return string
		 */
		private function translateMessage(MessageId $messageId, array $parameters)
		{
			$message = $this->messageProvider->getMessage($this->locale, $messageId, $parameters);

			if ($message === NULL) { // missing translate
				return $messageId->toString();
			}

			return ltrim($this->formatElements($message->getElements()));
		}


		/**
		 * @param  array<string|MessageElement> $elements
		 * @return string
		 */
		private function formatElements(array $elements)
		{
			$res = '';

			foreach ($elements as $element) {
				if ($element instanceof MessageElement) {
					if ($element->is('br')) {
						$res .= "\n";
						continue;
					}

					if ($element->is('ul')) {
						$res .= $this->formatList($element->getChildren(), FALSE);

					} elseif ($element->is('ol')) {
						$res .= $this->formatList($element->getChildren(), TRUE);

					} else {
						$res .= $this->formatElements($element->getChildren());
					}

				} else {
					$res .= $element;
				}
			}

			return $res;
		}


		/**
		 * @param  array<string|MessageElement> $elements
		 * @param  bool $isOrdered
		 * @param  non-negative-int $level
		 * @return string
		 */
		private function formatList(
			array $elements,
			$isOrdered,
			$level = 0
		)
		{
			$res = "\n";
			$levelPrefix = str_repeat("\t", $level);
			$counter = 0;

			foreach ($elements as $element) {
				if ($element instanceof MessageElement) {
					if ($element->is('ul')) {
						$res .= $this->formatList($element->getChildren(), FALSE, $level++);
						continue;
					}

					if ($element->is('ol')) {
						$res .= $this->formatList($element->getChildren(), FALSE, $level++);
						continue;
					}

					$res .= $levelPrefix;

					if ($element->is('li')) {
						if ($isOrdered) {
							$counter++;
							$res .= "\n" . $counter . '. ';

						} else {
							$res .= "\n- ";
						}
					}

					$res .= $this->formatElements($element->getChildren());

				} else {
					$res .= $levelPrefix . $element;
				}
			}

			return $res . "\n";
		}
	}
