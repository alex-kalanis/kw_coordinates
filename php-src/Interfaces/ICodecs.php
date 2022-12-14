<?php

namespace kalanis\kw_coordinates\Interfaces;


use kalanis\kw_coordinates\CoordinatesException;


interface ICodecs
{
    /**
     * Convert from format to Longitude and Latitude in INumbers
     * @param IFormatted $source
     * @param array<string, string|int|float|bool> $params
     * @throws CoordinatesException
     * @return INumbers
     */
    public function toLonLat(IFormatted $source, array $params): INumbers;

    /**
     * Convert from Longitude and Latitude in INumbers to format
     * @param INumbers $position
     * @param array<string, string|int|float|bool> $params
     * @throws CoordinatesException
     * @return IFormatted
     */
    public function fromLonLat(INumbers $position, array $params): IFormatted;
}
