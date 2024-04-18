<?php

	namespace Inteve\Translator;

	use Nette\Utils\Html;


	class DefaultHtmlTagFactory implements HtmlTagFactory
	{
		public function createTag(MessageElement $element)
		{
			if ($element->is('b', 'strong', 'em', 'i', 'sub', 'sup', 'span')) {
				return Html::el($element->getName(), $element->getAttributes([
					'class',
					'title',
				]));
			}

			if ($element->is('a')) {
				return Html::el($element->getName(), $element->getAttributes([
					'href',
					'target',
					'class',
					'title',
				]));
			}

			if ($element->is('ul', 'ol', 'li')) {
				return Html::el($element->getName(), $element->getAttributes([
					'class',
					'title',
				]));
			}

			if ($element->is('br')) {
				return Html::el('br');
			}

			return NULL;
		}
	}
