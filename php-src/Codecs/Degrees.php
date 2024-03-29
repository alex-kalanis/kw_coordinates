<?php

namespace kalanis\kw_coordinates\Codecs;


use kalanis\kw_coordinates\CoordinatesException;
use kalanis\kw_coordinates\Interfaces;
use kalanis\kw_coordinates\Support;


/**
 * Class Degrees
 * @package kalanis\kw_coordinates\Codecs
 * Format data from and into degrees, minutes and seconds
 */
class Degrees implements Interfaces\ICodecs
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
            $this->positiveNegative($this->toDegrees($position->getLongitude(), 360, 180), 'W', 'E'),
            $this->positiveNegative($this->toDegrees($position->getLatitude(), 180, 90), 'S', 'N'),
            $position->getAltitude()
        );
    }

    protected function toDegrees(float $number, int $upperLimit, int $halfLimit): string
    {
        $subs = abs($number) - intval(abs($number));
        $part = $number % $upperLimit;
        $part = 0 < $part ? $part + $subs : $part - $subs;
        $part = $part > $halfLimit ? ($part - $upperLimit) : (($part < -$halfLimit) ? ($part + $upperLimit) : $part);

        $min = abs($part - intval($part)) * 60;
        $sec = ($min - intval($min)) * 60;
        $dec = $part;
        return sprintf('%d°%d\'%01.5f"', $dec, intval($min), $sec);
    }

    protected function positiveNegative(string $number, string $forNegative, string $forPositive): string
    {
        return (('-' == $number[0]) ? substr($number, 1) . $forNegative : $number . $forPositive);
    }

    public function toLonLat(Interfaces\IFormatted $source, array $params): Interfaces\INumbers
    {
        return (clone $this->pos)->setData(
            $this->fromDegrees(strval($source->getLongitude()),360, 180, 'W', 'E'),
            $this->fromDegrees(strval($source->getLatitude()), 180, 90, 'S', 'N'),
            floatval(strval($source->getAltitude()))
        );
    }

    /**
     * @param string $value
     * @param int $upperLimit
     * @param int $halfLimit
     * @param string $forNegative
     * @param string $forPositive
     * @throws CoordinatesException
     * @return float
     */
    protected function fromDegrees(string $value, int $upperLimit, int $halfLimit, string $forNegative, string $forPositive): float
    {
        $format = '#([0-9\.]+)[^0-9\.]*(([0-9\.]+)[^0-9\.]*(([0-9\.]+)[^0-9\.]*)?)?#isu';
        if (!preg_match($format, $value, $matches)) {
            throw new CoordinatesException(sprintf('Malformed content value *%s*', $value));
        }
        $result = 0.0;
        if (isset($matches[1])) {
            $result += floatval($matches[1]);
        }
        if (isset($matches[3])) {
            $result += (floatval($matches[3]) / 60);
        }
        if (isset($matches[5])) {
            $result += (floatval($matches[5]) / (60 * 60));
        }
        $result *= $this->plusMinus($value, $forNegative, $forPositive);

        $subs = abs($result) - intval(abs($result));
        $part = $result % $upperLimit;
        $part = 0 < $part ? $part + $subs : $part - $subs;
        $part = $part > $halfLimit ? ($part - $upperLimit) : (($part < -$halfLimit) ? ($part + $upperLimit) : $part);

        return $part;
    }

    protected function plusMinus(string $input, string $forNegative, string $forPositive): float
    {
        return (false !== strpos($input, $forNegative)) ? -1 : 1 ;
    }
}
