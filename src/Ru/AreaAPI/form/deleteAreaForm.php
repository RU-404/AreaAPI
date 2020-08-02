<?php


namespace Ru\AreaAPI\form;


use pocketmine\form\Form;
use pocketmine\Player;
use Ru\AreaAPI\AreaAPI;

class deleteAreaForm implements Form
{

    public function jsonSerialize()
    {
        return [
            'type' => 'custom_form',
            'title' => '§l§fDELETEAREA',
            'content' => [
                [
                    'type' => 'input',
                    'text' => '삭제할 영역의 고유 아이디를 입력해주세요',
                    'placeholder' => '한번 삭제한 영역은 복구가 불가능합니다!'
                ],
                [
                    'type' => 'input',
                    'text' => "정말 삭제하시겠습니까?\n아래에 '동의합니다' 를 입력해주세요",
                    'placeholder' => '동의합니다'
                ]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null)return;
        if ($data[0] === null){
            $player->sendMessage(AreaAPI::$sy."삭제할 영역의 고유 ID를 입력해주세요!");
        }elseif ($data[1] === null){
            $player->sendMessage(AreaAPI::$sy."확인문자가 입력되지 않았습니다!");
        }elseif (!AreaAPI::getInstance()->isAreaExists($data[0])){
            $player->sendMessage(AreaAPI::$sy."해당 아이디와 일치하는 영역이 존재하지 않습니다!");
        }elseif ($data[1] !== "동의합니다"){
            $player->sendMessage(AreaAPI::$sy."확인문자가 제대로 입력되지 않았습니다!");
        }else{
            AreaAPI::getInstance()->deleteArea($data[0]);
            $player->sendMessage(AreaAPI::$sy."성공적으로 영역이 삭제되었습니다!");
        }
    }

}