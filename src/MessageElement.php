<?php

	namespace Inteve\Translator;


	class MessageElement
	{
		/** @var non-empty-string */
		private $name;

		/** @var array<non-empty-string, string> */
		private $attributes = [];

		/** @var array<string|MessageElement> */
		private $children = [];


		/**
		 * @param non-empty-string $name
		 * @param array<non-empty-string, string> $attributes
		 */
		public function __construct($name, array $attributes = [])
		{
			$this->name = strtolower($name);
			$this->attributes = $attributes;
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
		 * @return array<string|MessageElement>
		 */
		public function getChildren()
		{
			return $this->children;
		}


		/**
		 * @param  non-empty-string $name
		 * @param  array<non-empty-string, string> $attributes
		 * @return self
		 */
		public function create($name, array $attributes = [])
		{
			return $this->children[] = new self($name, $attributes);
		}


		/**
		 * @param  string $s
		 * @return $this
		 */
		public function addText($s)
		{
			$this->children[] = $s;
			return $this;
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
		 * @param  string $value
		 * @return $this
		 */
		public function setAttribute($name, $value)
		{
			$this->attributes[$name] = $value;
			return $this;
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
	}
