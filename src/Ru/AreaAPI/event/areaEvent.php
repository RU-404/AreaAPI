<?php


namespace Ru\AreaAPI\event;


use pocketmine\event\Event;
use Ru\AreaAPI\data\Area;

/**
 * Class areaEvent
 * @package Ru\AreaAPI\event
 */

abstract class areaEvent extends Event
{
    /**@var Area*/
    protected $area;

    /**
     * areaEvent constructor.
     * @param Area $area
     */
    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    /**
     * @return Area
     */
    public function getArea(): Area
    {
        return $this->area;
    }

}