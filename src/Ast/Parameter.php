<?php

	namespace Inteve\Translator\Ast;


	class Parameter implements Node
	{
		/** @var string */
		private $name;


		/**
		 * @param string $name
		 */
		public function __construct($name)
		{
			$this->name = $name;
		}


		public function format(array $parameters)
		{
			if (isset($parameters[$this->name])) {
				$value = $parameters[$this->name];

				if (is_scalar($value) || $value instanceof \Stringable) {
					return (string) $value;
				}
			}

			return '';
		}
	}
