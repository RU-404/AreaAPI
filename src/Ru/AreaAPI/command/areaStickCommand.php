<?php


namespace Ru\AreaAPI\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use Ru\AreaAPI\AreaAPI;

class areaStickCommand extends Command
{

    public function __construct()
    {
        parent::__construct('영역막대기', '영역 설정 막대기를 지급받습니다!', '/영역막대기', ['areaStick']);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player){
            $sender->sendMessage(AreaAPI::$sy."인게임에서 실행해주세요!");
        }elseif ($sender->hasPermission("Area") or $sender->hasPermission("Area.areaStick")){
            if ($sender->getInventory()->canAddItem(Item::get(280,3,1)->setCustomName("§e☆§fAREASTICK§e☆§r"))){
                $sender->getInventory()->addItem(Item::get(280,3,1)->setCustomName("§e☆§fAREASTICK§e☆§r"));
                $sender->sendMessage(AreaAPI::$sy."정상적으로 지급되었습니다!");
            }else{
                $sender->sendMessage(AreaAPI::$sy."인벤토리를 비운 후 시도해주세요!");
            }
        }else{
            $sender->sendMessage(AreaAPI::$sy."권한이 존재하지 않습니다!");
        }
    }

}