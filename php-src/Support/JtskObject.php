<?php

namespace kalanis\kw_coordinates\Support;


use kalanis\kw_coordinates\Interfaces\IFormatted;


/**
 * Class JtskObject
 * @package kalanis\kw_coordinates\Support
 * Transport values into other geographical system
 */
class JtskObject implements IFormatted
{
    /** @var float */
    protected float $x = 0.0;
    /** @var float */
    protected float $y = 0.0;
    /** @var float */
    protected float $z = 0.0;

    public function setData($x = '0', $y = '0', $z = '0'): IFormatted
    {
        $this->x = is_null($x) ? $this->x : floatval($x);
        $this->y = is_null($y) ? $this->y : floatval($y);
        $this->z = is_null($z) ? $this->z : floatval($z);
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->x;
    }

    public function getLatitude(): float
    {
        return $this->y;
    }

    public function getAltitude(): float
    {
        return $this->z;
    }
}
