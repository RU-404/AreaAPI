<?php


namespace Ru\AreaAPI\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Ru\AreaAPI\AreaAPI;
use Ru\AreaAPI\form\deleteAreaForm;

class deleteAreaCommand extends Command
{

    public function __construct()
    {
        parent::__construct("영역삭제", "영역을 삭제합니다", "/영역삭제", ['deletearea']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player){
            $sender->sendMessage(AreaAPI::$sy."인게임에서 실행해주세요!");
            return;
        }
        if ($sender->hasPermission("Area") or $sender->hasPermission("Area.deleteArea")){
            $sender->sendForm(new deleteAreaForm());
        }else{
            $sender->sendMessage(AreaAPI::$sy."권한이 존재하지 않습니다!");
        }
    }

}