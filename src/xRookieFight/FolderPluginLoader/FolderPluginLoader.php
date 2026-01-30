<?php

namespace xRookieFight\FolderPluginLoader;

use aquarelay\plugin\loader\PluginLoaderInterface;
use aquarelay\plugin\Plugin;
use aquarelay\plugin\PluginDescription;
use aquarelay\plugin\PluginException;
use aquarelay\ProxyServer;
use Symfony\Component\Yaml\Yaml;

readonly class FolderPluginLoader implements PluginLoaderInterface {

	public function __construct(
		private ProxyServer $server,
		private string $dataPath
	) {}

	public function canLoad(string $path) : bool
	{
	   return is_dir($path) && is_file($path . '/plugin.yml');
	}

	public function load(string $path) : ?Plugin
	{
		$pluginYml = $path . DIRECTORY_SEPARATOR . 'plugin.yml';
		if (!file_exists($pluginYml)) {
			return null;
		}

		$data = Yaml::parseFile($pluginYml);
		$description = PluginDescription::fromYaml($data);

		$vendor = $path . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
		if (file_exists($vendor)) {
			require_once $vendor;
		}

		$src = $path . DIRECTORY_SEPARATOR . 'src';
		if (is_dir($src)) {
			$this->registerAutoloader($src);
			$this->requireRecursive($src);
		}

		$main = $description->getMain();
		$mainFile = $src . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $main) . '.php';
		if (file_exists($mainFile)) {
			require_once $mainFile;
		}

		if (!class_exists($main)) {
			throw new PluginException("Main class $main not found on plugin: " . $description->getName());
		}

		$plugin = new $main();
		$plugin->setDescription($description);
		$plugin->setServer($this->server);
		$plugin->setDataFolder($this->dataPath . $description->getName());
		$plugin->setResourceFolder($path . DIRECTORY_SEPARATOR . 'resources');
		$plugin->onLoad();

		return $plugin;
	}

	private function registerAutoloader(string $src) : void
	{
		spl_autoload_register(static function(string $class) use ($src) : void {
			$file = $src . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
			if (file_exists($file)) {
				require_once $file;
			}
		});
	}

	private function requireRecursive(string $dir) : void
	{
		foreach (scandir($dir) ?: [] as $file) {
			if ($file === '.' || $file === '..') continue;

			$full = $dir . DIRECTORY_SEPARATOR . $file;
			if (is_dir($full)) {
				$this->requireRecursive($full);
			} elseif (str_ends_with($file, '.php')) {
				require_once $full;
			}
		}
	}
}