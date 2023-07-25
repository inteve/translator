<?php

	namespace Inteve\Translator\Ast;

	use Inteve\Translator\MessageElement;
	use Inteve\Translator\Locale;


	class MessageText
	{
		/** @var array<string|Node> */
		private $children;


		/**
		 * @param array<string|Node> $children
		 */
		public function __construct(array $children)
		{
			$this->children = $children;
		}


		/**
		 * @return array<string|Node>
		 */
		public function getChildren()
		{
			return $this->children;
		}


		/**
		 * @param  array<string, mixed> $parameters
		 * @return array<string|MessageElement>
		 */
		public function format(array $parameters, Locale $locale)
		{
			$res = [];

			foreach ($this->children as $child) {
				if ($child instanceof Node) {
					$res[] = $child->format($parameters, $locale);

				} else {
					$res[] = $child;
				}
			}

			return $res;
		}


		/**
		 * @param  array<string|Node> $children
		 * @return self
		 */
		public static function normalize(array $children)
		{
			$newParts = [];
			$stringBuffer = '';

			foreach ($children as $part) {
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

			return new self($newParts);
		}
	}
