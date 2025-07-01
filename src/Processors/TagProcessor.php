<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Processors;

	use Inteve\Translator\Ast;
	use Inteve\Translator\Utils;


	class TagProcessor implements \Inteve\Translator\MessageProcessor
	{
		/** @var \Inteve\Translator\MessageProcessor */
		private $textPartsProcessor;

		/** @var array<non-empty-string, TRUE> */
		private $emptyElements;


		/**
		 * @param array<non-empty-string> $emptyElements
		 */
		public function __construct(
			\Inteve\Translator\MessageProcessor $textPartsProcessor = NULL,
			array $emptyElements = ['br']
		)
		{
			$this->textPartsProcessor = $textPartsProcessor !== NULL ? $textPartsProcessor : new EntityProcessor(new ParametersProcessor);
			$this->emptyElements = [];

			foreach ($emptyElements as $emptyElement) {
				$this->emptyElements[strtolower($emptyElement)] = TRUE;
			}
		}


		public function processMessage($messageText)
		{
			$parser = new Utils\StringParser($messageText);
			return Ast\MessageText::normalize($this->parseContent($parser));
		}


		/**
		 * @return array<string|Ast\Node>
		 */
		private function parseContent(Utils\StringParser $parser)
		{
			$parts = [];

			while (!$parser->isEnd()) {
				if ($parser->isCurrent('<')) {
					if (($part = $this->tryParseElement($parser)) !== NULL) {
						$parts[] = $part;

					} else { // invalid tag
						$parts[] = $parser->consume(1);
					}

				} else {
					$text = $this->parseText($parser);

					foreach ($text as $textPart) {
						$parts[] = $textPart;
					}
				}
			}

			return $parts;
		}


		/**
		 * @return array<string|Ast\Node>
		 */
		private function parseText(Utils\StringParser $parser)
		{
			$text = $parser->consumeToText('<');
			return $this->textPartsProcessor->processMessage($text)->getChildren();
		}


		/**
		 * @return Ast\Element|NULL
		 */
		private function tryParseElement(Utils\StringParser $parser)
		{
			return $parser->tryParse(function (Utils\StringParser $parser) {
				$parser->consumeText('<');
				$name = $this->parseName($parser);
				$element = new Ast\Element($name);

				while ($parser->tryConsumeByMatch('\\s*') !== NULL) {
					if ($parser->isCurrent('/>')) { // self-closing
						$parser->consumeText('/>');
						return $element;
					}

					$attributeName = $this->parseName($parser);

					if ($parser->isCurrent('=')) {
						$parser->consumeText('=');
						$parser->consumeText('"');
						$attributeValue = (string) $parser->tryConsumeToText('"');
						$parser->consumeText('"');
						$element->setAttribute($attributeName, $attributeValue !== ''
							? $this->textPartsProcessor->processMessage($attributeValue)->getChildren()
							: $attributeValue
						);

					} else {
						$element->setAttribute($attributeName, TRUE);
					}
				}

				$parser->consumeText('>');

				if (isset($this->emptyElements[$element->getName()])) {
					return $element;
				}

				while (!$parser->isCurrent('</')) {
					if ($parser->isCurrent('<')) {
						$subElement = $this->tryParseElement($parser);

						if ($subElement !== NULL) {
							$element->addNode($subElement);

						} else {
							$element->addText($parser->consume(1));
						}
					} else {
						$content = $parser->consumeToText('<');
						$textParts = $this->textPartsProcessor->processMessage($content)->getChildren();

						foreach ($textParts as $textPart) {
							if ($textPart instanceof Ast\Node) {
								$element->addNode($textPart);

							} else {
								$element->addText($textPart);
							}
						}
					}
				}

				$parser->consumeText('</' . $name . '>');

				return $element;
			});
		}


		/**
		 * @return non-empty-string
		 */
		private function parseName(Utils\StringParser $parser)
		{
			$name = $parser->consumeByMatch('[a-zA-Z]([a-zA-Z0-9-]+)?');
			assert($name !== '');
			return $name;
		}
	}
