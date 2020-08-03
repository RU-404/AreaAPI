<?php


namespace Ru\AreaAPI\form;


use pocketmine\form\Form;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Ru\AreaAPI\AreaAPI;

class makeAreaForm implements Form
{
    /**@var Vector3*/
    public $pos1;

    /**@var Vector3*/
    public $pos2;

    /**@var int*/
    public $levelId;

    /**
     * makeAreaForm constructor.
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     * @param int $levelId
     */
    public function __construct(Vector3 $pos1, Vector3 $pos2, int $levelId)
    {
        $this->pos1 = $pos1;
        $this->pos2 = $pos2;
        $this->levelId = $levelId;
    }

    /**
     * @return mixed|void
     */

    public function jsonSerialize()
    {
        return [
            'type' => 'custom_form',
            'title' => '§l§fMAKEAREA',
            'content' => [
                [
                    'type' => 'input',
                    'text' => '영역의 이름을 설정하세요',
                    'placeholder' => 'AREA!'
                ],
                [
                    'type' => 'input',
                    'text' => '영역의 아이디를 설정해주세요',
                    'placeholder' => 'STRING'
                ]
            ]
        ];
    }

    /**
     * @param Player $player
     * @param mixed $data
     */

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null)return;
        if ($data[0] === null){
            $player->sendMessage(AreaAPI::$sy."영역의 이름을 입력해주세요!");
        }elseif ($data[1] === null){
            $player->sendMessage(AreaAPI::$sy."영역의 고유 ID를 입력해주세요!");
        }elseif (mb_strlen($data[0]) >= 20){
            $player->sendMessage(AreaAPI::$sy."영역의 이름은 20글자 이상이어선 안됩니다!");
        }elseif (mb_strlen($data[1]) >= 20){
            $player->sendMessage(AreaAPI::$sy."영역의 고유 ID는 20자 이상이어선 안됩니다!");
        }else{
            $a = AreaAPI::getInstance()->makeArea($this->pos1,$this->pos2,$this->levelId,$data[0],$data[1],null);
            if ($a === true){
                $player->sendMessage(AreaAPI::$sy."영역이 성공적으로 생성되었습니다! [ 영역의 이름 : {$data[0]} ], [ 영역의 ID : {$data[1]} ]");
            }elseif ($a === null){
                $player->sendMessage(AreaAPI::$sy."생성하려고 하는 곳에 이미 영역이 존재합니다!");
            }elseif ($a === false){
                $player->sendMessage(AreaAPI::$sy."영역 생성에 실패하였습니다!");
            }else return;
        }
    }

}