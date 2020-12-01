<?php

namespace HubCore\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use HubCore\HubCore as Main;

class HubCommand extends Command implements PluginIdentifiableCommand {

	private $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		parent::__construct(
			"hub",
			"Teleport to hub",
			"/hub",
			["lobby", "spawn"]
		);
		$this->setPermission("hub.command");
	}

	public function getPlugin() : Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $label, array $args) {
		if (!$this->testPermission($sender)) {
			return;
		}

		$spawnLocation = $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation();
		if ($sender instanceof Player) {
			if (isset($args[0])) {
				if ($this->plugin->getServer()->getPlayer($args[0])) {
					$player = $this->plugin->getServer()->getPlayer($args[0]);
					$player->teleport($spawnLocation);
					$player->sendMessage(TextFormat::GREEN . "You have been teleported to spawn");
					$sender->sendMessage(TextFormat::GREEN . "Teleported " . $player->getName() . " to spawn");
				} else {
					$sender->sendMessage(TextFormat::RED . "Player not found");
				}
			} else {
				$sender->teleport($spawnLocation);
				$sender->sendMessage(TextFormat::GREEN . "You have been teleported to spawn");
			}
		} else { // console
			if (isset($args[0])) {
				if ($this->plugin->getServer()->getPlayer($args[0])) {
					$player = $this->plugin->getServer()->getPlayer($args[0]);
					$player->teleport($spawnLocation);
					$player->sendMessage(TextFormat::GREEN . "You have been teleported to spawn");
					$sender->sendMessage(TextFormat::GREEN . "Teleported " . $player->getName() . " to spawn");
				} else {
					$sender->sendMessage(TextFormat::RED . "Player not found");
				}
			} else {
				$sender->sendMessage(TextFormat::RED . "Please enter a player name");
			}
		}
	}

}
