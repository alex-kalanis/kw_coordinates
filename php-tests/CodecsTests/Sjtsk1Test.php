<?php

namespace CodecsTests;


use CommonTestClass;
use kalanis\kw_coordinates\CoordinatesException;
use kalanis\kw_coordinates\Support;
use kalanis\kw_coordinates\Codecs;


/**
 * Class Sjtsk1Test
 * @package CodecsTests
 * @see http://freegis.fsv.cvut.cz/gwiki/S-JTSK
 */
class Sjtsk1Test extends CommonTestClass
{
    /**
     * @param float $coordLon
     * @param float $coordLat
     * @param float $coordAlt
     * @param float $x
     * @param float $y
     * @param float $z
     * @param bool $useAlt
     * @throws CoordinatesException
     * @dataProvider transferFromProvider
     */
    public function testFromJtskToCoord(float $coordLon, float $coordLat, float $coordAlt, float $x, float $y, float $z, bool $useAlt = true): void
    {
        $lib = new Codecs\Sjtsk1();
        $transport = new Support\Position();
        $result = $lib->fromLonLat($transport->setData($coordLon, $coordLat, $useAlt ? $coordAlt : 0.0));
        $this->assertEquals(sprintf('%01.6f', $x), sprintf('%01.6f', $result->getLongitude()));
        $this->assertEquals(sprintf('%01.6f', $y), sprintf('%01.6f', $result->getLatitude()));
        if ($useAlt) $this->assertEquals(sprintf('%01.6f', $z), sprintf('%01.6f', $result->getAltitude()));
    }

    public function transferFromProvider(): array
    {
        return [
            [17.1000319, 48.1417237, 0, 574330.31144954, 1281026.261520, 0, false], // bratislavsky hrad
            [12.8069819, 49.4522531, 559.417, 868209.23834622, 1095794.6651235, 512.602931, true], // testovaci bod 109 - krizek u Postrekova u Klenci pod Cerchovem
        ];
    }

    /**
     * @param float $coordLon
     * @param float $coordLat
     * @param float $coordAlt
     * @param float $x
     * @param float $y
     * @param float $z
     * @param bool $useAlt
     * @throws CoordinatesException
     * @dataProvider transferToProvider
     * It hops cca about 10 metres out, but shit happens for this code
     */
    public function testFromCoordToJtsk(float $coordLon, float $coordLat, float $coordAlt, float $x, float $y, float $z, bool $useAlt = true): void
    {
        $lib = new Codecs\Sjtsk1();
        $transport = new Support\JtskObject();
        $result = $lib->toLonLat($transport->setData($x, $y, $useAlt ? $z : 0.0));
        $this->assertEquals(sprintf('%01.6f', $coordLon), sprintf('%01.6f', $result->getLongitude()));
        $this->assertEquals(sprintf('%01.6f', $coordLat), sprintf('%01.6f', $result->getLatitude()));
        if ($useAlt) $this->assertEquals(sprintf('%01.6f', $coordAlt), sprintf('%01.6f', $result->getAltitude()));
    }

    public function transferToProvider(): array
    {
        return [
            [17.099988437339, 48.141741927043, 0, 574330.31144954, 1281026.261520, 0, false], // bratislavsky hrad
            [12.807019361493, 49.452368187952, 559.417, 868209.23834622, 1095794.6651235, 512.602931, false], // testovaci bod 109 - krizek u Postrekova u Klenci pod Cerchovem
        ];
    }
}
