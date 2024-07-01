<?php

namespace PlayerPots;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\player\Player;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function getPotions(Player $player): int {
        return count(array_filter($player->getInventory()->getContents(), function(Item $item): bool {
            return $item->getTypeId() === ItemTypeIds::SPLASH_POTION;
        }));
    }

    public function onDeath(PlayerDeathEvent $event) {
        /**@var Player $player */
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();
        if (!$cause instanceof EntityDamageByEntityEvent) {
            return;
        }
        if ($player instanceof Player) {
            /**@var Player $killer */
            $killer = $cause->getDamager();
            $potskiller = $this->getPotions($killer);
            $potsplayer = $this->getPotions($player);

            $event->setDeathMessage("§a" . $killer->getName() . "§2[" . $potskiller . "]" . "§7 killed " . "§c" . $player->getName() . "§4[" . $potsplayer . "]§r");
        }
        else {
            $this->getServer()->broadcastMessage("§e§lOMG");
            $event->setDeathMessage("");
            $event->setXpDropAmount(0);
        }
    }
}