<?php

	namespace Inteve\Translator;


	class MessageElement
	{
		/** @var non-empty-string */
		private $name;

		/** @var array<non-empty-string, string|bool> */
		private $attributes = [];

		/** @var array<string|self> */
		private $children = [];


		/**
		 * @param non-empty-string $name
		 * @param array<non-empty-string, string|bool> $attributes
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
		 * @param  non-empty-string $name
		 * @return bool
		 */
		public function is($name)
		{
			return $this->name === strtolower($name);
		}


		/**
		 * @return array<string|self>
		 */
		public function getChildren()
		{
			return $this->children;
		}


		/**
		 * @param  string $name
		 * @return bool
		 */
		public function hasAttribute($name)
		{
			return isset($this->attributes[$name]);
		}


		/**
		 * @param  non-empty-string $name
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
		 * @param  non-empty-string $name
		 * @param  array<non-empty-string, string|bool> $attributes
		 * @param  array<string|self> $children
		 * @return self
		 */
		public static function el($name, array $attributes = [], $children = [])
		{
			return new self($name, $attributes, $children);
		}
	}
