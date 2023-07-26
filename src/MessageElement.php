<?php

	namespace Inteve\Translator;


	class MessageElement
	{
		/** @var non-empty-string */
		private $name;

		/** @var array<non-empty-lowercase-string, string|bool> */
		private $attributes = [];

		/** @var array<string|self> */
		private $children = [];


		/**
		 * @param non-empty-lowercase-string $name
		 * @param array<non-empty-lowercase-string, string|bool> $attributes
		 * @param array<string|self> $children
		 */
		public function __construct(
			$name,
			array $attributes = [],
			array $children = []
		)
		{
			$this->name = strtolower($name);
			$this->attributes = $attributes;
			$this->children = $children;
		}


		/**
		 * @param  non-empty-string ...$name
		 * @return bool
		 */
		public function is(...$name)
		{
			foreach ($name as $n) {
				if ($this->name === strtolower($n)) {
					return TRUE;
				}
			}

			return FALSE;
		}


		/**
		 * @return non-empty-lowercase-string
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * @return array<string|self>
		 */
		public function getChildren()
		{
			return $this->children;
		}


		/**
		 * @param  non-empty-lowercase-string[]|NULL $whitelist
		 * @return array<non-empty-lowercase-string, string|bool>
		 */
		public function getAttributes(array $whitelist = NULL)
		{
			if ($whitelist !== NULL) {
				$res = [];

				foreach ($whitelist as $item) {
					if (isset($this->attributes[$item])) {
						$res[$item] = $this->attributes[$item];
					}
				}

				return $res;
			}

			return $this->attributes;
		}


		/**
		 * @param  non-empty-lowercase-string $name
		 * @return bool
		 */
		public function hasAttribute($name)
		{
			return isset($this->attributes[$name]);
		}


		/**
		 * @param  non-empty-lowercase-string $name
		 * @return string|bool
		 */
		public function getAttribute($name)
		{
			if (!isset($this->attributes[$name])) {
				throw new InvalidStateException("Missing attribute '$name'.");
			}

			return $this->attributes[$name];
		}


		/**
		 * @return string
		 */
		public function toText()
		{
			$res = '';

			foreach ($this->children as $child) {
				if ($child instanceof self) {
					$res .= $child->toText();

				} else {
					$res .= $child;
				}
			}

			return $res;
		}


		/**
		 * @param  non-empty-lowercase-string $name
		 * @param  array<non-empty-lowercase-string, string|bool> $attributes
		 * @param  array<string|self> $children
		 * @return self
		 */
		public static function el($name, array $attributes = [], $children = [])
		{
			return new self($name, $attributes, $children);
		}
	}
