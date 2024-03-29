<?php

namespace HubCore;

use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;
use HubCore\command\HubCommand;
use HubCore\command\SetHubCommand;

class HubCore extends PluginBase implements Listener {

	public $prefix = TextFormat::GRAY . "[" . TextFormat::GOLD . "System" . TextFormat::GRAY . "]";
	public $error = TextFormat::GRAY . "[" . TextFormat::DARK_RED . "ERROR" . TextFormat::GRAY . "]";
	public $warn = TextFormat::GRAY . "[" . TextFormat::RED . "Warn" . TextFormat::GRAY . "]";
	public $warning = TextFormat::GRAY . "[" . TextFormat::YELLOW . "Warning" . TextFormat::GRAY . "]";
	public $report = TextFormat::GRAY . "[" . TextFormat::RED . "REPORT" . TextFormat::GRAY . "]";

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getPluginManager()->addPermission(new Permission("hub.command", "Allows you to teleport to hub", Permission::DEFAULT_TRUE));
		$this->getServer()->getPluginManager()->addPermission(new Permission("hub.set", "Allows you to set the hub", Permission::DEFAULT_OP));
		$this->getServer()->getCommandMap()->register("hub", new HubCommand($this));
		$this->getServer()->getCommandMap()->register("sethub", new SetHubCommand($this));
	}

	public function onJoin(PlayerJoinEvent $join) {
		$player = $join->getPlayer();
		$spawnLocation = $this->getServer()->getDefaultLevel()->getSpawnLocation();
		$player->teleport($spawnLocation);
	}

	public function onPlayerDeath(PlayerDeathEvent $event) {
		$event->setDeathMessage('You died');
		$player = $event->getPlayer();
		$spawnLocation = $this->getServer()->getDefaultLevel()->getSpawnLocation();
		$player->teleport($spawnLocation);
	}

	public function onDamage(EntityDamageEvent $event) {
		$player = $event->getEntity();
		$default = $this->getServer()->getDefaultLevel();
		if ($player->getLevel() === $default) {
			$event->getCause() === EntityDamageEvent::CAUSE_FALL;
			$event->setCancelled();
		}
	}

	public function onHurt(EntityDamageEvent $ev) {
		$entity = $ev->getEntity();
		$vector = new Vector3(
			$entity->getLevel()->getSpawnLocation()->getX(), 
			$entity->getPosition()->getY(), 
			$entity->getLevel()->getSpawnLocation()->getZ()
		);
		$radius = $this->getServer()->getSpawnRadius();
		if (($entity instanceof Player) && ($entity->getPosition()->distance($vector) <= $radius)) {
			$ev->setCancelled();
		}
	}

}
