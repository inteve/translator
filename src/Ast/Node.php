<?php

	namespace Inteve\Translator\Ast;

	use Inteve\Translator\MessageElement;


	interface Node
	{
		/**
		 * @param  array<string, mixed> $parameters
		 * @return string|MessageElement
		 */
		function format(array $parameters);
	}
