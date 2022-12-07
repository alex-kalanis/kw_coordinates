<?php

namespace kalanis\kw_coordinates\Support;


use kalanis\kw_coordinates\Interfaces\IFormatted;


/**
 * Class DegreesObject
 * @package kalanis\kw_coordinates\Support
 * Transport values into other geographical system
 */
class DegreesObject implements IFormatted
{
    /** @var string X  +-180° */
    protected $longitude = '0';
    /** @var string Y +-90° */
    protected $latitude = '0';
    /** @var string Z +-10000m */
    protected $altitude = '0';

    public function setData($longitude = '0', $latitude = '0', $altitude = '0'): IFormatted
    {
        $this->longitude = is_null($longitude) ? $this->longitude : strval($longitude);
        $this->latitude = is_null($latitude) ? $this->latitude : strval($latitude);
        $this->altitude = is_null($altitude) ? $this->altitude : strval($altitude);
        return $this;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getAltitude(): string
    {
        return $this->altitude;
    }
}
