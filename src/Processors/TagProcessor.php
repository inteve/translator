<?php

	namespace Inteve\Translator\Processors;

	use Inteve\Translator\Ast;
	use Inteve\Translator\Utils;
	use Nette\Utils\Strings;


	class TagProcessor implements \Inteve\Translator\MessageProcessor
	{
		/** @var Utils\ParametersParser|NULL */
		private $parametersParser;


		public function processMessage($messageText)
		{
			if ($this->parametersParser === NULL) {
				$this->parametersParser = new Utils\ParametersParser;
			}

			$parser = new Utils\StringParser($messageText);
			return Ast\MessageText::normalize($this->parseContent($parser, $this->parametersParser));
		}


		/**
		 * @return array<string|Ast\Node>
		 */
		private function parseContent(Utils\StringParser $parser, Utils\ParametersParser $parametersParser)
		{
			$parts = [];

			while (!$parser->isEnd()) {
				if ($parser->isCurrent('<')) {
					if (($part = $this->tryParseElement($parser, $parametersParser)) !== NULL) {
						$parts[] = $part;

					} else { // invalid parameter
						$parts[] = $parser->consume(1);
					}

				} else {
					$text = $this->parseText($parser, $parametersParser);

					if (is_array($text)) {
						foreach ($text as $textPart) {
							$parts[] = $textPart;
						}

					} else {
						$parts[] = $text;
					}
				}
			}

			return $parts;
		}


		/**
		 * @return string|array<string|Ast\Node>
		 */
		private function parseText(Utils\StringParser $parser, Utils\ParametersParser $parametersParser)
		{
			$text = $parser->consumeToText('<');

			if (Strings::contains($text, '{$')) {
				return $parametersParser->parse($text);
			}

			return $text;
		}


		/**
		 * @return Ast\Element|NULL
		 */
		private function tryParseElement(Utils\StringParser $parser, Utils\ParametersParser $parametersParser)
		{
			return $parser->tryParse(function (Utils\StringParser $parser) use ($parametersParser) {
				$parser->consumeText('<');
				$name = $this->parseName($parser);
				$element = new Ast\Element($name);

				while ($parser->tryConsumeByMatch('\\s*') !== NULL) {
					$attributeName = $this->parseName($parser);

					if ($parser->isCurrent('=')) {
						$parser->consumeText('=');
						$parser->consumeText('"');
						$attributeValue = (string) $parser->tryConsumeToText('"');
						$parser->consumeText('"');
						$element->setAttribute($attributeName, ($attributeValue !== '' && Strings::contains($attributeValue, '{$'))
							? $parametersParser->parse($attributeValue)
							: $attributeValue
						);

					} else {
						$element->setAttribute($attributeName, TRUE);
					}
				}

				$parser->consumeText('>');

				while (!$parser->isCurrent('</')) {
					if ($parser->isCurrent('<')) {
						$subElement = $this->tryParseElement($parser, $parametersParser);

						if ($subElement !== NULL) {
							$element->addNode($subElement);

						} else {
							$element->addText($parser->consume(1));
						}
					} else {
						$content = $parser->consumeToText('<');

						if (Strings::contains($content, '{$')) {
							foreach ($parametersParser->parse($content) as $contentPart) {
								if ($contentPart instanceof Ast\Node) {
									$element->addNode($contentPart);

								} else {
									$element->addText($contentPart);
								}
							}

						} else {
							$element->addText($content);
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
