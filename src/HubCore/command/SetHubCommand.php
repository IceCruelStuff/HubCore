<?php

namespace HubCore\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\math\Vector3;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use HubCore\HubCore as Main;

class SetHubCommand extends Command implements PluginIdentifiableCommand {

	private $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		parent::__construct(
			"sethub",
			"Set the hub",
			"/sethub <x> <y> <z>",
			["setlobby", "setspawn"]
		);
		$this->setPermission("hub.set");
	}

	public function getPlugin() : Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $label, array $args) {
		if (!$this->testPermission($sender)) {
			return;
		}

		if ($sender instanceof Player) {
			if (isset($args[0])) {
				if (isset($args[2])) { // check for all three coordinates if the first one is entered
					if (isset($args[3])) { // check if level is entered
						if ($this->plugin->getServer()->isLevelGenerated($args[3])) { // check if level exists
							$this->plugin->getServer()->setDefaultLevel($args[3]);
						} else {
							$sender->sendMessage(TextFormat::RED . "An error has occurred. The level entered does not exist.");
							return;
						}
					} else {
						$this->plugin->getServer()->setDefaultLevel($sender->getLevel());
					}

					$x = $args[0];
					$y = $args[1];
					$z = $args[2];

					$pos = new Vector3($x, $y, $z);
					$pos->round();

					$level = $sender->getServer()->getDefaultLevel();
					$level->setSpawnLocation($pos);
				} else {
					$sender->sendMessage(TextFormat::RED . "Please enter all three coordinates");
				}
			} else { // use player position if no coordinates were entered
				$this->plugin->getServer()->setDefaultLevel($sender->getLevel());

				$x = $sender->getX();
				$y = $sender->getY();
				$z = $sender->getZ();

				$pos = new Vector3($x, $y, $z);
				$pos->round();

				$level = $sender->getServer()->getDefaultLevel();
				$level->setSpawnLocation($pos);
			}
		} else { // console
			if (isset($args[3])) { // last one is for level
				if ($this->plugin->getServer()->isLevelGenerated($args[3])) { // check if level exists
					$this->plugin->getServer()->setDefaultLevel($args[3]);
				} else {
					$sender->sendMessage(TextFormat::RED . "An error has occurred. The level entered does not exist.");
					return;
				}
				$x = $args[0];
				$y = $args[1];
				$z = $args[2];

				$pos = new Vector3($x, $y, $z);
				$pos->round();

				$level = $sender->getServer()->getDefaultLevel();
				$level->setSpawnLocation($pos);
			} else {
				$sender->sendMessage(TextFormat::RED . "Please enter all three coordinates and the world name");
			}
		}
	}

}
