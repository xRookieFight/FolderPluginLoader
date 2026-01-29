<?php

namespace xRookieFight\FolderPluginLoader;

use aquarelay\plugin\Plugin;

class Main extends Plugin {

	public function onEnable(): void
	{
		$server = $this->getServer();
		$server->getPluginManager()->registerLoader(new FolderPluginLoader($server, $server->getDataPath()));
	}
}