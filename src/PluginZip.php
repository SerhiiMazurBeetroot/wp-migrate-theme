<?php

namespace MigrateTheme;

use MigrateTheme\Scanner;

defined('ABSPATH') || exit;

class PluginZip
{
	public static function zipMake($source, $destination, $exclude = [])
	{
		if (is_array($exclude) && extension_loaded('zip')) {

			if (file_exists($source)) {
				$zip = new \ZipArchive();
				if ($zip->open($destination, \ZIPARCHIVE::CREATE)) {
					$source = realpath($source);

					if (is_dir($source)) {
						$iterator = new \RecursiveDirectoryIterator($source);
						// skip dot files while iterating 
						$iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
						$files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $file) {
							$file = realpath($file);
							$path = str_replace($source . '/', '', $file);

							//Check if it has to zip the file/folder
							if (!Scanner::match($path, $exclude)) {

								if (is_dir($file)) {
									$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
								} else if (is_file($file)) {
									$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
								}
							}
						}
					} else if (is_file($source)) {
						$zip->addFromString(basename($source), file_get_contents($source));
					}
				}
				return $zip->close();
			}
		}
		return false;
	}

	public static function zipDeleteEmptyDir($file)
	{
		$zip = new \ZipArchive();

		if ($zip->open($file) === TRUE) {
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$stat = $zip->statIndex($i);

				if ($stat['size'] === 0) {
					$zip->deleteName($stat['name']);
				}
			}

			$zip->close();
		}
	}
}
