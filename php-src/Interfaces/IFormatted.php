<?php

namespace kalanis\kw_coordinates\Interfaces;


/**
 * Interface IFormatted
 * @package kalanis\kw_coordinates\Interfaces
 * Formatted representation of coordinates
 */
interface IFormatted
{
    /**
     * @param mixed $longitude
     * @param mixed $latitude
     * @param mixed $altitude
     * @return IFormatted
     */
    public function setData($longitude = '0', $latitude = '0', $altitude = '0'): self;

    /**
     * @return mixed
     */
    public function getLongitude();

    /**
     * @return mixed
     */
    public function getLatitude();

    /**
     * @return mixed
     */
    public function getAltitude();
}
