<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Processors;

	use Inteve\Translator\Ast;
	use Inteve\Translator\MessageProcessor;


	class EntityProcessor implements MessageProcessor
	{
		/** @var MessageProcessor|NULL */
		private $textProcessor;


		public function __construct(MessageProcessor $textProcessor = NULL)
		{
			$this->textProcessor = $textProcessor;
		}


		public function processMessage($messageText)
		{
			if ($this->textProcessor === NULL) {
				return new Ast\MessageText([$this->decodeEntities($messageText)]);
			}

			$messageText = $this->textProcessor->processMessage($messageText);
			$parts = [];

			foreach ($messageText->getChildren() as $child) {
				if (is_string($child)) {
					if ($child !== '') {
						$parts[] = $this->decodeEntities($child);
					}

				} else {
					$parts[] = $child;
				}
			}

			return new Ast\MessageText($parts);
		}


		/**
		 * @param  non-empty-string $messageText
		 * @return non-empty-string
		 */
		private function decodeEntities($messageText)
		{
			$messageText = html_entity_decode($messageText, ENT_QUOTES | ENT_SUBSTITUTE | ENT_XHTML, 'UTF-8');
			// ENT_XHTML - allows 'all' entities (like ENT_XML) + &apos;

			if ($messageText === '') {
				throw new \Inteve\Translator\InvalidStateException('Decoded string is empty.');
			}

			return $messageText;
		}
	}
