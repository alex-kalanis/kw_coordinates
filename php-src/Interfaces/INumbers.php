<?php

namespace kalanis\kw_coordinates\Interfaces;


/**
 * Interface INumbers
 * @package kalanis\kw_coordinates\Interfaces
 * Numeric representation of coordinates
 */
interface INumbers
{
    public function setData(?float $longitude = 0.0, ?float $latitude = 0.0, ?float $altitude = 0.0): self;

    public function getLongitude(): float;

    public function getLatitude(): float;

    public function getAltitude(): float;
}
