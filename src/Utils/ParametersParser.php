<?php

	namespace Inteve\Translator\Utils;

	use Inteve\Translator\Parameter;


	class ParametersParser
	{
		/**
		 * @param  non-empty-string $text
		 * @return array<string|Parameter>
		 */
		public function parse($text)
		{
			$parser = new StringParser($text);
			return $this->parseBegin($parser);
		}


		/**
		 * @param  StringParser $parser
		 * @return array<string|Parameter>
		 */
		private function parseBegin(StringParser $parser)
		{
			$parts = [];

			while (!$parser->isEnd()) {
				if ($parser->isCurrent('{$')) {
					if (($part = $this->parseParameter($parser)) !== NULL) {
						$parts[] = $part;

					} else { // invalid parameter
						$parts[] = $parser->consume(1);
					}

				} else {
					$parts[] = $this->parseText($parser);
				}
			}

			return $this->normalizeParts($parts);
		}


		/**
		 * @return string
		 */
		private function parseText(StringParser $parser)
		{
			return $parser->consumeToText('{$');
		}


		/**
		 * @return Parameter|NULL
		 */
		private function parseParameter(StringParser $parser)
		{
			return $parser->tryParse(function (StringParser $parser) {
				$parser->consumeText('{$');
				$name = $parser->consumeByMatch('[a-zA-Z0-9]([a-zA-Z0-9.])*(\\[[a-zA-Z0-9]\\])*');
				$parser->consumeText('}');
				return new Parameter($name);
			});
		}


		/**
		 * @param  array<string|Parameter> $parts
		 * @return array<string|Parameter>
		 */
		private function normalizeParts(array $parts)
		{
			$newParts = [];
			$stringBuffer = '';

			foreach ($parts as $part) {
				if (is_string($part)) {
					$stringBuffer .= $part;

				} else {
					if ($stringBuffer !== '') {
						$newParts[] = $stringBuffer;
						$stringBuffer = '';
					}
					$newParts[] = $part;
				}
			}

			if ($stringBuffer !== '') {
				$newParts[] = $stringBuffer;
				$stringBuffer = '';
			}

			return $newParts;
		}
	}
