<?php


namespace Ru\AreaAPI\listener;


use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use Ru\AreaAPI\AreaAPI;

class eventListener implements Listener
{
    /**@var AreaAPI*/
    private $plugin;

    /**@var array*/
    public $data;

    /**@var self*/
    private static $instance;

    /**
     * eventListener constructor.
     * @param AreaAPI $AreaAPI
     */
    public function __construct(AreaAPI $AreaAPI)
    {
        $this->plugin = $AreaAPI;
        self::$instance = $this;
    }

    /**
     * @return static
     */

    public static function getInstance() : self
    {
        return self::$instance;
    }

    /**
     * @param PlayerInteractEvent $event
     */

    public function onTouch(PlayerInteractEvent $event)
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        $item = $event->getItem();
        $id = $item->getId();
        $meta = $item->getDamage();
        $name = $event->getItem()->getCustomName();

        if (($id === 280 and $meta === 3 and $name === "§e☆§fAREASTICK§e☆§r") and ($player->hasPermission("Area") or $player->hasPermission("Area.makeArea")) and $event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_AIR){
            $pos = [$block->getFloorX(),$block->getFloorY(),$block->getFloorZ(),$block->getLevel()->getFolderName()];

            $this->setPos1($player,$pos,$block);
        }
    }

    /**
     * @param BlockBreakEvent $event
     */

    public function onBreak(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        $item = $event->getItem();
        $id = $item->getId();
        $meta = $item->getDamage();
        $name = $event->getItem()->getCustomName();

        if (($id === 280 and $meta === 3 and $name === "§e☆§fAREASTICK§e☆§r") and ($player->hasPermission("Area") or $player->hasPermission("Area.makeArea"))){
            $pos = [$block->getFloorX(),$block->getFloorY(),$block->getFloorZ(),$block->getLevel()->getFolderName()];

            $this->setPos2($player,$pos,$block);
            $event->setCancelled(true);
        }
    }

    private function setPos1(Player $player, array $pos, Block $block)
    {
        $this->data["{$player->getName()}-1"] = [$block->getFloorX(),$block->getFloorY(),$block->getFloorZ(),$block->getLevel()->getFolderName()];
        $player->sendMessage(AreaAPI::$sy."1번째 좌표가 지정되었습니다! [ {$pos[0]}, {$pos[1]}, {$pos[2]} ], [ 월드 : {$pos[3]} ]");
    }

    private function setPos2(Player $player, array $pos, Block $block)
    {
        $this->data["{$player->getName()}-2"] = [$block->getFloorX(),$block->getFloorY(),$block->getFloorZ(),$block->getLevel()->getFolderName()];
        $player->sendMessage(AreaAPI::$sy."2번째 좌표가 지정되었습니다! [ {$pos[0]}, {$pos[1]}, {$pos[2]} ], [ 월드 : {$pos[3]} ]");
    }

}