<?php

declare(strict_types=1);

namespace Ru\AreaAPI;

/*
 *  _____                _____
 * |_   _|              |  __ \
 *   | |  __ _ _ __ ___ | |__) |   _
 *   | | / _` | '_ ` _ \|  _  / | | |
 *  _| || (_| | | | | | | | \ \ |_| | ___
 * |_____\__,_|_| |_| |_|_|  \_\__,_|(___)


 *
 * @author : IamRu_
 * @api : 3.x.x
 * @github : github.com/RU-404
 */

use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Ru\AreaAPI\command\areaStickCommand;
use Ru\AreaAPI\command\makeAreaCommand;
use Ru\AreaAPI\data\Area;
use Ru\AreaAPI\listener\eventListener;

/**
 * Class AreaAPI
 * @package Ru\AreaAPI
 */

class AreaAPI extends PluginBase{

    /**@var Config*/
    public $data;

    /**@var array*/
    public $db;

    /**@var string*/
    public static $sy = "§b[ §f! §b]§f ";

    /**@var self*/
    private static $instance;

    /**
     * PluginBase Part
     */

    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->data = new Config($this->getDataFolder().'Areas.yml',Config::YAML);
        $this->db = $this->data->getAll();

        $this->getServer()->getPluginManager()->registerEvents(new eventListener($this),$this);

        $this->getServer()->getCommandMap()->register('areaStick',new areaStickCommand());
        $this->getServer()->getCommandMap()->register('makeArea',new makeAreaCommand());
    }

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function save()
    {
        $this->data->setAll($this->db);
        $this->data->save();
    }

    /**
     * @return static
     */

    public static function getInstance() : self
    {
        return self::$instance;
    }

    /**
     * Main Functions
     */

    /**
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     * @param Vector3|null $warpPos
     * @param string $levelName
     * @param string $name
     * @param string $id
     *
     * @return bool
     */

    public function makeArea(Vector3 $pos1, Vector3 $pos2, ?Vector3 $warpPos = null, string $levelName, string $name, string $id) : ?bool{
        if (!isset($this->db)){
            $area = new Area($pos1,$pos2,$warpPos,$levelName,$name,$id);
            $this->db[$id] = $area->jsonSerialize();
            $this->save();
            return true;
        }else{
            foreach ($this->db as $value){
                $area1 = Area::deSerialize($value);
                if ($area1->isOverlap($pos1,$pos2,$levelName)){
                    return null;
                }elseif ($area1->getId() === $id or $area1->getName() === $name){
                    return false;
                }else continue;
            }
            $area = new Area($pos1,$pos2,$warpPos,$levelName,$name,$id);
            $this->db[$id] = $area->jsonSerialize();
            $this->save();
            return true;
        }
    }

    /**
     * @param string $id
     * @return bool|null
     */

    public function deleteArea(string $id) : ?bool
    {
        if (!isset($this->db[$id])){
            return null;
        }else{
            unset($this->db[$id]);
            $this->save();
            return true;
        }
    }

    /**
     * @param string $id
     * @return bool
     */

    public function isAreaExists(string $id) : bool
    {
        return isset($this->db[$id]);
    }

    /**
     * @return Area[]|null
     */

    public function getAllAreas() : ?array
    {
        if (!isset($this->db)){
            return null;
        }else{
            $areas = [];
            foreach ($this->db as $are){
                $area = Area::deSerialize($are);
                array_push($areas,$area);
            }
            return $areas;
        }
    }

    /**
     * @param string $id
     * @return Area|null
     */

    public function getArea(string $id) : ?Area
    {
        return isset($this->db[$id]) ? Area::deSerialize($this->db[$id]) : null;
    }
}