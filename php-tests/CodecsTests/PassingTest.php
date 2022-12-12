<?php

namespace CodecsTests;


use CommonTestClass;
use kalanis\kw_coordinates\CoordinatesException;
use kalanis\kw_coordinates\Support;
use kalanis\kw_coordinates\Codecs;


class PassingTest extends CommonTestClass
{
    /**
     * @param float $coordLon
     * @param float $coordLat
     * @param float $coordAlt
     * @param string $x
     * @param string $y
     * @param string $z
     * @param bool $useAlt
     * @throws CoordinatesException
     * @dataProvider transferProvider
     */
    public function testPassFrom(float $coordLon, float $coordLat, float $coordAlt, string $x, string $y, string $z, bool $useAlt = true): void
    {
        $lib = new Codecs\Passing();
        $transport = new Support\Position();
        $result = $lib->fromLonLat($transport->setData($coordLon, $coordLat, $useAlt ? $coordAlt : 0.0));

        $this->assertEquals(sprintf('%01.6f', $x), sprintf('%01.6f', $result->getLongitude()));
        $this->assertEquals(sprintf('%01.6f', $y), sprintf('%01.6f', $result->getLatitude()));
        if ($useAlt) $this->assertEquals(sprintf('%01.6f', $z), sprintf('%01.6f', $result->getAltitude()));
    }

    /**
     * @param float $coordLon
     * @param float $coordLat
     * @param float $coordAlt
     * @param string $x
     * @param string $y
     * @param string $z
     * @param bool $useAlt
     * @throws CoordinatesException
     * @dataProvider transferProvider
     */
    public function testPassTo(float $coordLon, float $coordLat, float $coordAlt, string $x, string $y, string $z, bool $useAlt = true): void
    {
        $lib = new Codecs\Passing();
        $transport = new Support\DegreesObject();
        $result = $lib->toLonLat($transport->setData($x, $y, $useAlt ? $z : 0.0));

        $this->assertEquals(sprintf('%01.6f', $coordLon), sprintf('%01.6f', $result->getLongitude()));
        $this->assertEquals(sprintf('%01.6f', $coordLat), sprintf('%01.6f', $result->getLatitude()));
        if ($useAlt) $this->assertEquals($coordAlt, $result->getAltitude());
    }

    /**
     * @return array
     */
    public function transferProvider(): array
    {
        return [
            [48.1417237, 17.1000319, 305, '48.1417237', '17.1000319', '305', false], // bratislavsky hrad
            [-548.1417237, -17.1000319, 305, '-548.1417237', '-17.1000319', '305', true], // nekde jinde
            [12.8069888666667, -49.4522626972222, 512.30, '12.8069888666667', '-49.4522626972222', '512.3', true], // testovaci bod 109
            [-12.8069888666667, 4995.4522626972222, 559.417, '-12.8069888666667', '4995.4522626972222', '0.0', false], // zase jinde
        ];
    }
}
