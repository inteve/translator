<?php

	namespace Inteve\Translator;

	use Nette\Utils\Html;


	class HtmlTranslator implements Translator
	{
		/** @var Locale */
		private $locale;

		/** @var MessageProvider */
		private $messageProvider;

		/** @var HtmlTagFactory */
		private $htmlTagFactory;


		public function __construct(
			Locale $locale,
			MessageProvider $messageProvider,
			HtmlTagFactory $htmlTagFactory = NULL
		)
		{
			$this->locale = $locale;
			$this->messageProvider = $messageProvider;
			$this->htmlTagFactory = $htmlTagFactory !== NULL ? $htmlTagFactory : new DefaultHtmlTagFactory;
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
			$newTarget = $this->htmlTagFactory->createTag($element);

			if ($newTarget !== NULL) {
				$target->addHtml($newTarget);
				$target = $newTarget;
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
