<?php

namespace kalanis\kw_coordinates\Interfaces;


use kalanis\kw_coordinates\CoordinatesException;


interface ICodecs
{
    /**
     * Convert from format to Longitude and Latitude in INumbers
     * @param IFormatted $source
     * @throws CoordinatesException
     * @return INumbers
     */
    public function toLonLat(IFormatted $source): INumbers;

    /**
     * Convert from Longitude and Latitude in INumbers to format
     * @param INumbers $position
     * @throws CoordinatesException
     * @return IFormatted
     */
    public function fromLonLat(INumbers $position): IFormatted;
}
