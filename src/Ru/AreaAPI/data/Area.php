<?php


namespace Ru\AreaAPI\data;

use JsonSerializable;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use Ru\AreaAPI\AreaAPI;
use Ru\AreaAPI\exception\AreaException;

/**
 * Class Area
 * @package Ru\AreaAPI\data
 */

class Area implements JsonSerializable
{

    /**@var Vector3*/
    private $pos1;

    /**@var Vector3*/
    private $pos2;

    /**@var Vector3*/
    private $warpPos;

    /**@var int*/
    private $levelId;

    /**@var string*/
    private $name;

    /**@var string*/
    private $id;

    /**
     * Area constructor.
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     * @param Vector3|null $warpPos
     * @param int $levelId
     * @param string $name
     * @param string $id
     */

    public function __construct(Vector3 $pos1, Vector3 $pos2, int $levelId, string $name, string $id, ?Vector3 $warpPos = null)
    {
        $this->setPos1(new Vector3($pos1->getFloorX(),$pos1->getFloorY(),$pos1->getFloorZ()));
        $this->setPos2(new Vector3($pos2->getFloorX(),$pos2->getFloorY(),$pos2->getFloorZ()));
        if (!isset($warpPos)){
            $this->setWarpPos($this->getPos1());
        }else{
            $this->setWarpPos(new Vector3($warpPos->getFloorX(),$warpPos->getFloorY(),$warpPos->getFloorZ()));
        }
        $this->setLevelId($levelId);
        $this->setName($name);
        $this->setId($id);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLevelId(): int
    {
        return $this->levelId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Vector3
     */
    public function getPos1(): Vector3
    {
        return $this->pos1;
    }

    /**
     * @return Vector3
     */
    public function getPos2(): Vector3
    {
        return $this->pos2;
    }

    /**
     * @return Vector3|null
     */
    public function getWarpPos(): Vector3
    {
        return $this->warpPos;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $levelId
     */
    public function setLevelId(int $levelId): void
    {
        $this->levelId = $levelId;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Vector3 $pos1
     */
    public function setPos1(Vector3 $pos1): void
    {
        $this->pos1 = $pos1;
    }

    /**
     * @param Vector3 $pos2
     */
    public function setPos2(Vector3 $pos2): void
    {
        $this->pos2 = $pos2;
    }

    /**
     * @param Vector3 $warpPos
     */
    public function setWarpPos(Vector3 $warpPos): void
    {
        $this->warpPos = $warpPos;
    }

    /**
     * @param Player $player
     */

    public function teleportToWarpPos(Player $player) :void
    {
        $level = AreaAPI::getInstance()->getServer()->getLevel($this->levelId);

        if ($level === null)throw new AreaException("The non-existent world is set as the object's world.");

        $player->teleport(new Position($this->warpPos,$level),$player->getYaw(),$player->getPitch());
    }

    /**
     * @param Vector3 $pos3
     * @param Vector3 $pos4
     * @param int $levelId1
     * @return bool
     */

    public function isOverlap(Vector3 $pos3,Vector3 $pos4,int $levelId1) : bool
    {
        $pos1 = $this->getPos1();
        $pos2 = $this->getPos2();

        $levelId = $this->levelId;

        $xXxX = [$pos1->getX(),$pos2->getX(),$pos3->getX(),$pos4->getX()];
        $zZzZ = [$pos1->getZ(),$pos2->getZ(),$pos3->getZ(),$pos4->getZ()];

        $xXxX1 = $xXxX;
        $zZzZ1 = $zZzZ;

        sort($xXxX);
        sort($zZzZ);

        $disX = abs($xXxX[3] - $xXxX[0]);
        $disZ = abs($zZzZ[3] - $zZzZ[0]);

        $disArea1X = abs($xXxX1[0] - $xXxX1[1]);
        $disArea2X = abs($xXxX1[2] - $xXxX1[3]);

        $disArea1Z = abs($zZzZ1[0] - $zZzZ1[1]);
        $disArea2Z = abs($zZzZ1[2] - $zZzZ1[3]);

        if (($disX <= $disArea1X + $disArea2X) and ($disZ <= $disArea1Z + $disArea2Z) and $levelId === $levelId1){
            return true;
        }else{
            return false;
        }

    }

    /**
     * @param Player $player
     * @return bool
     */

    public function isPlayerInArea(Player $player) : bool
    {
        $pos1 = $this->getPos1();
        $pos2 = $this->getPos2();

        $levelId = $this->levelId;

        if ((($pos1->getX()<=$player->getX() and $pos2->getX()>=$player->getX()) or (($pos1->getX()>=$player->getX() and $pos2->getX()<=$player->getX()))) and (($pos1->getZ()<=$player->getZ() and $pos2->getZ()>=$player->getZ()) or (($pos1->getZ()>=$player->getZ() and $pos2->getZ()<=$player->getZ()))) and $levelId === $player->getLevel()->getId()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'pos1' => array($this->pos1->getX(),$this->pos1->getY(),$this->pos1->getZ()),
            'pos2' => array($this->pos2->getX(),$this->pos2->getY(),$this->pos2->getZ()),
            'levelId' => $this->getLevelId(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'warpPos' => array($this->warpPos->getX(),$this->warpPos->getY(),$this->warpPos->getZ())
        ];
    }

    public static function deSerialize(array $data) : Area
    {
        return new self(new Vector3($data['pos1'][0],$data['pos1'][1],$data['pos1'][2]),
            new Vector3($data['pos2'][0],$data['pos2'][1],$data['pos2'][2]),
        $data['levelId'],
        $data['name'],
        $data['id'],
            new Vector3($data['warpPos'][0],$data['warpPos'][1],$data['warpPos'][2])
        );
    }
}