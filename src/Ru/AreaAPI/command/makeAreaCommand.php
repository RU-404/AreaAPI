<?php


namespace Ru\AreaAPI\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Ru\AreaAPI\AreaAPI;
use Ru\AreaAPI\form\makeAreaForm;
use Ru\AreaAPI\listener\eventListener;

class makeAreaCommand extends Command
{

    public function __construct()
    {
        parent::__construct('영역생성',"영역을 생성합니다","/영역생성",["makearea"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player){
            $sender->sendMessage(AreaAPI::$sy."인게임에서 실행해주세요!");
            return;
        }
        if (isset(eventListener::getInstance()->data[$sender->getName()."-1"]) and isset(eventListener::getInstance()->data[$sender->getName()."-2"])){
            if (eventListener::getInstance()->data[$sender->getName()."-1"][3] === eventListener::getInstance()->data[$sender->getName()."-2"][3]){
                $sender->sendForm(new makeAreaForm(
                    new Vector3(eventListener::getInstance()->data[$sender->getName()."-1"][0],
                    eventListener::getInstance()->data[$sender->getName()."-1"][1],
                    eventListener::getInstance()->data[$sender->getName()."-1"][2]),
                    new Vector3(eventListener::getInstance()->data[$sender->getName()."-2"][0],
                        eventListener::getInstance()->data[$sender->getName()."-2"][1],
                        eventListener::getInstance()->data[$sender->getName()."-2"][2])
                    ,eventListener::getInstance()->data[$sender->getName()."-1"][3]));
            }else{
                $sender->sendMessage(AreaAPI::$sy."두 좌표의 월드가 다릅니다!");
                return;
            }
        }else{
            $sender->sendMessage(AreaAPI::$sy."좌표가 지정되지 않았습니다!");
        }
    }

}