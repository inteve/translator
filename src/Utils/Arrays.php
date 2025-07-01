<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Utils;


	class Arrays
	{
		public function __construct()
		{
			throw new \Inteve\Translator\StaticClassException('This is static class.');
		}


		/**
		 * @param  array<string, mixed> $arr
		 * @param  string|NULL $keyPrefix
		 * @return array<string, string>
		 */
		public static function flattenAssoc(array $arr, $keyPrefix = NULL)
		{
			$res = [];

			self::recursiveWalk($arr, function ($val, $key) use (&$res) {
				if (is_scalar($val)) {
					if (array_key_exists($key, $res)) {
						throw new \Inteve\Translator\InvalidStateException("Value for key '$key' already exists.");
					}

					$res[(string) $key] = (string) $val;
				}
			}, $keyPrefix);

			return $res;
		}


		/**
		 * @param  array<mixed> $arr
		 * @param  string|NULL $keyPrefix
		 * @return void
		 */
		private static function recursiveWalk(array $arr, callable $callback, $keyPrefix = NULL)
		{
			foreach ($arr as $key => $value) {
				$itemKey = $keyPrefix !== NULL ? ($keyPrefix . '.' . $key) : $key;

				if (is_array($value)) {
					self::recursiveWalk($value, $callback, $itemKey);

				} else {
					call_user_func_array($callback, [$value, $itemKey]);
				}
			}
		}
	}
