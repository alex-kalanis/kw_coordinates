<?php

namespace kalanis\kw_coordinates\Codecs;


use kalanis\kw_coordinates\Interfaces;
use kalanis\kw_coordinates\Support;


/**
 * Class Passing
 * @package kalanis\kw_coordinates\Codecs
 * Just pass through
 * Good when you convert between more codecs and one of them is the basic format
 */
class Passing implements Interfaces\ICodecs
{
    protected Interfaces\INumbers $pos;
    protected Interfaces\IFormatted $out;

    public function __construct(?Interfaces\INumbers $position = null, ?Interfaces\IFormatted $output = null)
    {
        $this->pos = $position ?: new Support\Position();
        $this->out = $output ?: new Support\DegreesObject();
    }

    public function fromLonLat(Interfaces\INumbers $position, array $params): Interfaces\IFormatted
    {
        return (clone $this->out)->setData(
            $position->getLongitude(),
            $position->getLatitude(),
            $position->getAltitude(),
        );
    }

    public function toLonLat(Interfaces\IFormatted $source, array $params): Interfaces\INumbers
    {
        return (clone $this->pos)->setData(
            is_null($source->getLongitude()) ? null : floatval(strval($source->getLongitude())),
            is_null($source->getLatitude()) ? null : floatval(strval($source->getLatitude())),
            is_null($source->getAltitude()) ? null : floatval(strval($source->getAltitude()))
        );
    }
}
