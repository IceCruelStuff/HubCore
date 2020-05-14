<?php

namespace HubCore;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;

class HubCore extends PluginBase implements Listener {

	public $prefix = "§7[§6System§7]";
	public $error = "§7[§4ERROR§7]";
	public $warn = "§7[§cWarn§7]";
	public $warning = "§7[§eWarning§7]";
	public $report = "§7[§cREPORT§7]";

	public function onLoad() : void {
		$this->getLogger()->info("§7Loading HubCore...");
	}

	public function onEnable() : void {
		$this->getLogger()->info("HubCore has been enabled");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDisable() : void {
		$this->getLogger()->info("HubCore has been disabled");
	}

	public function onJoin(PlayerJoinEvent $join) {
		$player = $join->getPlayer();
		$spawnLocation = $this->getServer()->getDefaultLevel()->getSpawnLocation();
		$player->teleport($spawnLocation);
	}

	public function onPlayerDeath(PlayerDeathEvent $event) {
		$event->setDeathMessage("You died");
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

	public function onHurt(EntityDamageEvent $eve) {
		$entity = $eve->getEntity();
		$v = new Vector3(
			$entity->getLevel()->getSpawnLocation()->getX(), 
			$entity->getPosition()->getY(), 
			$entity->getLevel()->getSpawnLocation()->getZ(),
		);
		$radius = $this->getServer()->getSpawnRadius();
		if(($entity instanceof Player) && ($entity->getPosition()->distance($v) <= $radius)){
			$eve->setCancelled();
		}
	}

}
