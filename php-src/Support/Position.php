<?php

namespace kalanis\kw_coordinates\Support;


use kalanis\kw_coordinates\Interfaces\INumbers;


/**
 * Class Position
 * @package kalanis\kw_coordinates\Support
 * Transport values into other geographical system
 */
class Position implements INumbers
{
    /** @var float X  +-180° */
    protected $longitude = 0.0;
    /** @var float Y +-90° */
    protected $latitude = 0.0;
    /** @var float Z +-10000m */
    protected $altitude = 0.0;

    public function setData(?float $longitude = 0.0, ?float $latitude = 0.0, ?float $altitude = 0.0): INumbers
    {
        $this->longitude = is_null($longitude) ? $this->longitude : $longitude;
        $this->latitude = is_null($latitude) ? $this->latitude : $latitude;
        $this->altitude = is_null($altitude) ? $this->altitude : $altitude;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getAltitude(): float
    {
        return $this->altitude;
    }
}
