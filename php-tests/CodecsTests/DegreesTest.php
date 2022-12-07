<?php

namespace CodecsTests;


use CommonTestClass;
use kalanis\kw_coordinates\CoordinatesException;
use kalanis\kw_coordinates\Support;
use kalanis\kw_coordinates\Codecs;


class DegreesTest extends CommonTestClass
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
     * @dataProvider transferFromProvider
     */
    public function testFromDegToCoord(float $coordLon, float $coordLat, float $coordAlt, string $x, string $y, string $z, bool $useAlt = true): void
    {
        $lib = new Codecs\Degrees();
        $transport = new Support\Position();
        $result = $lib->fromLonLat($transport->setData($coordLon, $coordLat, $useAlt ? $coordAlt : 0.0));

        $this->assertEquals($x, $result->getLongitude());
        $this->assertEquals($y, $result->getLatitude());
        if ($useAlt) $this->assertEquals($z, $result->getAltitude());
    }

    /**
     * @return array
     */
    public function transferFromProvider(): array
    {
        return [
            [48.1417237, 17.1000319, 305, '48°8\'30.20532"E', '17°6\'0.11484"N', '305', false], // bratislavsky hrad
            [-548.1417237, -17.1000319, 305, '171°51\'29.79468"E', '17°6\'0.11484"S', '305', true], // nekde jinde
            [12.8069888666667, -49.4522626972222, 512.30, '12°48\'25.15992"E', '49°27\'8.14571"S', '512.3', true], // testovaci bod 109
            [-12.8069888666667, 4995.4522626972222, 559.417, '12°48\'25.15992"W', '44°32\'51.85429"S', '0.0', false], // zase jinde
        ];
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
     * @dataProvider transferToProvider
     */
    public function testFromCoordToDeg(float $coordLon, float $coordLat, float $coordAlt, string $x, string $y, string $z, bool $useAlt = true): void
    {
        $lib = new Codecs\Degrees();
        $transport = new Support\DegreesObject();
        $result = $lib->toLonLat($transport->setData($x, $y, $useAlt ? $z : 0.0));

        $this->assertEquals(sprintf('%01.6f', $coordLon), sprintf('%01.6f', $result->getLongitude()));
        $this->assertEquals(sprintf('%01.6f', $coordLat), sprintf('%01.6f', $result->getLatitude()));
        if ($useAlt) $this->assertEquals($coordAlt, $result->getAltitude());
    }

    /**
     * @return array
     */
    public function transferToProvider(): array
    {
        return [
            [48.1417237, 17.1000319, 305, '48°8\'30.20532"E', '17°6\'0.11484"N', '305', false], // bratislavsky hrad
            [171.8582763, -17.1000319, 305, '171°51\'29.79468"E', '17°6\'0.11484"S', '305', true], // nekde jinde
            [-7.1930111333333, -49.4522626972222, 512.30, '352°48\'25.15992"E', '49°27\'8.14571"S', '512.3', true], // testovaci bod 109
            [-12.8069888666667, 24.452262697222068, 0.0, '12°48\'25.15992"W', '4475°32\'51.85429"S', '559.417', false], // zase jinde
        ];
    }

    /**
     * @throws CoordinatesException
     */
    public function testFromCoordDied(): void
    {
        $lib = new Codecs\Degrees();
        $transport = new Support\DegreesObject();
        $this->expectException(CoordinatesException::class);
        $lib->toLonLat($transport->setData('not-a-numbers', 'not-any-number'));
    }
}
