<?php

	namespace Inteve\Translator\Ast;

	use Inteve\Translator\MessageElement;
	use Inteve\Translator\Locale;


	class Element implements Node
	{
		/** @var non-empty-string */
		private $name;

		/** @var array<non-empty-string, string|Node|bool|array<string|Node>> */
		private $attributes = [];

		/** @var array<string|Node> */
		private $children = [];


		/**
		 * @param non-empty-string $name
		 * @param array<non-empty-string, string|Node|bool|array<string|Node>> $attributes
		 */
		public function __construct($name, array $attributes = [])
		{
			$this->name = strtolower($name);
			$this->attributes = $attributes;
		}


		/**
		 * @return non-empty-string
		 */
		public function getName()
		{
			return $this->name;
		}


		public function format(array $parameters, Locale $locale)
		{
			$attrs = [];

			foreach ($this->attributes as $attributeName => $attributeValue) {
				if (is_array($attributeValue)) {
					$concatedAttributeValue = '';

					foreach ($attributeValue as $attributeValuePart) {
						if ($attributeValuePart instanceof Node) {
							$concatedAttributeValue .= $attributeValuePart->format($parameters, $locale);

						} else {
							$concatedAttributeValue .= $attributeValuePart;
						}
					}

					$attrs[$attributeName] = $concatedAttributeValue;

				} elseif ($attributeValue instanceof Node) {
					$attrs[$attributeName] = $attributeValue->format($parameters, $locale);

				} else {
					$attrs[$attributeName] = $attributeValue;
				}
			}

			$children = [];

			foreach ($this->children as $child) {
				if ($child instanceof Node) {
					$children[] = $child->format($parameters, $locale);

				} else {
					$children[] = $child;
				}
			}

			return new MessageElement($this->name, $attrs, $children);
		}


		/**
		 * @return $this
		 */
		public function addNode(Node $node)
		{
			$this->children[] = $node;
			return $this;
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
		 * @param  non-empty-string $name
		 * @param  string|Node|bool|array<string|Node> $value
		 * @return $this
		 */
		public function setAttribute($name, $value)
		{
			$this->attributes[$name] = $value;
			return $this;
		}


		/**
		 * @param  non-empty-string $name
		 * @param  array<non-empty-string, string|Node|bool|array<string|Node>> $attributes
		 * @param  array<string|Node> $children
		 * @return self
		 */
		public static function create(
			$name,
			array $attributes = [],
			array $children = []
		)
		{
			$el = new self($name, $attributes);
			$el->children = $children;
			return $el;
		}
	}
