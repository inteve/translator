<?php

	declare(strict_types=1);

	namespace Inteve\Translator;

	use Nette\Utils\Html;


	interface HtmlTagFactory
	{
		/**
		 * @return Html|NULL
		 */
		function createTag(MessageElement $element);
	}
