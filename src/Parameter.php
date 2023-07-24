<?php

	namespace Inteve\Translator;


	class Parameter
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


		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * @param  mixed $value
		 * @return string|\Stringable
		 */
		public function toString($value)
		{
			if (is_scalar($value) || $value instanceof \Stringable) {
				return (string) $value;
			}

			return '';
		}
	}
