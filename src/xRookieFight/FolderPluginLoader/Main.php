<?php

namespace xRookieFight\FolderPluginLoader;

use aquarelay\plugin\Plugin;

class Main extends Plugin {

	public function onEnable(): void
	{
		$server = $this->getServer();
		$pluginsPath = $server->getDataPath() . 'plugins' . DIRECTORY_SEPARATOR;
		$server->getPluginManager()->registerLoader(new FolderPluginLoader($server, $pluginsPath));
		$server->getPluginManager()->loadPlugins();
	}
}