<?php

namespace MigrateTheme;

defined('ABSPATH') || exit;

class Scanner
{
	public static function findDirs($folder)
	{
		// Get blocks from directory by absolute path
		return self::DirectoryIteratorToArray($folder, new \DirectoryIterator($folder));
	}

	public static function getThemeStructure($themeDir, $files)
	{
		$response = [];
		foreach ($files as $key => $filePath) {
			$path = str_replace($themeDir, '', $filePath);

			// Get TOP Dir name
			$arrayPath = array_values(
				array_filter(
					explode(DIRECTORY_SEPARATOR, $path),
					function ($val) {
						return strlen($val);
					}
				)
			);

			if (count($arrayPath) > 1) {
				$mainDir = $arrayPath[0];
			} else {
				$mainDir = 'root';
			}

			$response[$mainDir]['type'] = $mainDir;
			$response[$mainDir]['title'] = ucfirst($mainDir);
			$response[$mainDir]['files'][$key] = $path;
		}

		return $response;
	}

	private static function DirectoryIteratorToArray($themeDir, $it)
	{
		$result = array();
		foreach ($it as $key => $child) {
			if ($child->isDot()) {
				continue;
			}

			$pathName = $child->getPathName();
			$path = str_replace($themeDir . '/', '', $pathName);

			if (!self::match($path, ['node_modules'])) {
				if ($child->isDir()) {
					$subit = new \DirectoryIterator($child->getPathname());

					$result = array_merge($result, self::DirectoryIteratorToArray($themeDir, $subit));
				} else {
					$result[] = $path;
				}
			}
		}

		return $result;
	}

	public static function match($item, $array)
	{
		$matching = array("\\" => "[\/|\\\]", "/" => "[\/|\\\]");
		foreach ($array as $i) {
			$str = strtr($i, $matching); //creates the regex
			if (preg_match("/" . $str . "/i", $item))
				return true;
		}

		return false;
	}

	public static function merge_arrays(&$array1, &$array2)
	{
		for ($i = 0; $i < sizeof($array2); ++$i) {
			$array1[] = $array2[$i];
		}
	}
}
