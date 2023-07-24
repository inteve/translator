<?php

	namespace Inteve\Translator\Ast;

	use Inteve\Translator\MessageElement;


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
		 * @param  array<string, mixed> $parameters
		 * @return array<string|MessageElement>
		 */
		public function format(array $parameters)
		{
			$res = [];

			foreach ($this->children as $child) {
				if ($child instanceof Node) {
					$res[] = $child->format($parameters);

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
