<?php

namespace PlayerPots;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\player\Player;

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
        $player = $event->getEntity();
        $killer = $player->getLastDamageCause()->getDamager();

        if ($killer instanceof Player) {
            $potskiller = $this->getPotions($killer);
            $potsplayer = $this->getPotions($player);

            $event->setDeathMessage("§a" . $killer->getName() . "§2[" . $potskiller . "]" . "§7 killed " . "§c" . $player->getName() . "§4[" . $potsplayer . "]§r");
        }
    }
}
