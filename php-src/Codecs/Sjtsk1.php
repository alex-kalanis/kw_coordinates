<?php

namespace kalanis\kw_coordinates\Codecs;


use kalanis\kw_coordinates\Interfaces;
use kalanis\kw_coordinates\Support;


/**
 * Class Sjtsk1
 * @package kalanis\kw_coordinates\Codecs
 * @link https://blog.drndos.sk/2013/03/how-to-convert-s-jtsk-coordinates-to-wsg84/
 * @link https://www.pecina.cz/krovak.html
 *
 * SJTSKtoWSG84.java Filip Bednárik <drndos@drndos.sk>
 * package sk.datalan.semweb.czechpediacrawler;
 *
 * toWgs is port from java class
 * fromWgs is port from JavaScript code
 *
 * Do not ask me how both works...; but used, tested and works
 */
class Sjtsk1 implements Interfaces\ICodecs
{
    const FG_SHIFT = 17.66666666666666;
    const dX_SHIFT = 589;
    const dY_SHIFT = 76;
    const dZ_SHIFT = 480;

    /** @var Interfaces\INumbers */
    protected $pos = null;
    /** @var Interfaces\IFormatted */
    protected $out = null;

    public function __construct(?Interfaces\INumbers $position = null, ?Interfaces\IFormatted $output = null)
    {
        $this->pos = $position ?: new Support\Position();
        $this->out = $output ?: new Support\JtskObject();
    }

    public function fromLonLat(Interfaces\INumbers $position): Interfaces\IFormatted
    {
        $d2r = pi() / 180;
        $a = 6378137.0;
        $f1 = 298.257223563;
        $dx = -570.69;
        $dy = -85.69;
        $dz = -462.84;
        $wx = 4.99821 / 3600 * pi() / 180;
        $wy = 1.58676 / 3600 * pi() / 180;
        $wz = 5.2611 / 3600 * pi() / 180;
        $m = -3.543e-6;

        $B = $position->getLatitude() * $d2r;
        $L = $position->getLongitude() * $d2r;
        $H = $position->getAltitude();

        $e2 = 1 - $this->sqr(1 - (1 / $f1));
        $rho = $a / sqrt(1 - ($e2 * $this->sqr(sin($B))));
        $x1 = ($rho + $H) * cos($B) * cos($L);
        $y1 = ($rho + $H) * cos($B) * sin($L);
        $z1 = (((1 - $e2) * $rho) + $H) * sin($B);

        $x2 = $dx + (1 + $m) * ($x1 + ($wz * $y1) - ($wy * $z1));
        $y2 = $dy + (1 + $m) * (((-$wz) * $x1) + $y1 + ($wx * $z1));
        $z2 = $dz + (1 + $m) * (($wy * $x1) - ($wx * $y1) + $z1);

        $a = 6377397.15508;
        $f1 = 299.152812853;
        $ab = $f1 / ($f1 - 1);
        $p = sqrt($this->sqr($x2) + $this->sqr($y2));
        $e2 = 1 - $this->sqr(1 - (1 / $f1));
        $th = atan(($z2 * $ab) / $p);
        $st = sin($th);
        $ct = cos($th);
        $t = ($z2 + ($e2 * $ab * $a * ($st * $st * $st))) / ($p - ($e2 * $a * ($ct * $ct * $ct)));

        $B = atan($t);
        $H = sqrt(1 + ($t * $t)) * ($p - ($a / sqrt(1 + ((1 - $e2) * $t * $t))));
        $L = 2 * atan($y2 / ($p + $x2));

        $a = 6377397.15508;
        $e = 0.081696831215303;
        $n = 0.97992470462083;
        $rho0 = 12310230.12797036;
        $sinUQ = 0.863499969506341;
        $cosUQ = 0.504348889819882;
        $sinVQ = 0.420215144586493;
        $cosVQ = 0.907424504992097;
        $alpha = 1.000597498371542;
        $k2 = 1.00685001861538;

        $sinB = sin($B);
        $t = (1 - ($e * $sinB)) / (1 + ($e * $sinB));
        $t = ($this->sqr(1 + $sinB) / (1 - $this->sqr($sinB))) * exp($e * log($t));
        $t = $k2 * exp($alpha * log($t));

        $sinU = ($t - 1) / ($t + 1);
        $cosU = sqrt(1 - ($sinU * $sinU));
        $V = $alpha * $L;
        $sinV = sin($V);
        $cosV = cos($V);
        $cosDV = ($cosVQ * $cosV) + ($sinVQ * $sinV);
        $sinDV = ($sinVQ * $cosV) - ($cosVQ * $sinV);
        $sinS = ($sinUQ * $sinU) + ($cosUQ * $cosU * $cosDV);
        $cosS = sqrt(1 - $sinS * $sinS);
        $sinD = ($sinDV * $cosU) / $cosS;
        $cosD = sqrt(1 - ($sinD * $sinD));

        $eps = $n * atan($sinD / $cosD);
        $rho = $rho0 * exp(-$n * log((1 + $sinS) / $cosS));

        $CX = $rho * sin($eps);
        $CY = $rho * cos($eps);

        $obj = clone $this->out;
        return $obj->setData(
            $CX,
            $CY,
            $H
        );
    }

    protected function sqr(float $x): float
    {
        return $x * $x;
    }

    public function toLonLat(Interfaces\IFormatted $source): Interfaces\INumbers
    {
        $sjtskLatLong = $this->toSJTSKLatLong(floatval(strval($source->getLatitude())), floatval(strval($source->getLongitude())));
        return $this->toWSG84LatLong($sjtskLatLong[0], $sjtskLatLong[1]);
    }

    /**
     * @param float $x
     * @param float $y
     * @return array<float>
     */
    protected function toSJTSKLatLong(float $x, float $y): array
    {
        $finRad = pi() * 49.5 / 180;
        $la0deg = 42 + 31.00 / 60 + 31.41725 / 3600;
        $ro1 = sqrt($x * $x + $y * $y);
        $rGauss = 6380065.5402;
        $betakv = pi() * 11.5 / 180;
        $fi0 = 59 + (42.00 / 60) + (42.69689 / 3600);
        $a = 6377397.155;
        $b = 6356078.96325;

        $e2 = sqrt(($a * $a - $b * $b) / ($b * $b));
        $m = cos($betakv);
        $vn = sqrt(1 + $e2 * $e2 * cos($finRad) * cos($finRad));
        $gamma = atan2($y, $x);
        $c = $rGauss * sin($betakv) / $m / pow(tan($betakv / 2), $m);

        $fi0rad = $fi0 * pi() / 180;
        $fin = atan2(tan($finRad), $vn);
        $lambv = $gamma / $m;
        $betav = 2 * atan(pow($ro1 / $c, 1 / $m));
        $fi = asin(cos($betav) * sin($fi0rad) - sin($betav) * cos($fi0rad) * cos($lambv));

        $n = sin($finRad) / sin($fin);
        $la = asin(sin($betav) * sin($lambv) / cos($fi));
        $la0 = $la0deg * pi() / 180;

        $laRad = ($la0 - $la) / $n;
        $laFerro = $laRad * 180 / pi();
        $sjtskLong = $laFerro - static::FG_SHIFT;
        $e1 = sqrt(($a * $a - $b * $b) / ($a * $a));

        $k = tan(pi() / 4 + $fin / 2) / (pow(tan(pi() / 4 + $finRad / 2), $n) * pow((1 - $e1 * sin($finRad)) / (1 + $e1 * sin($finRad)), $n * $e1 / 2));
        $fik = 2 * atan(pow(1 / $k * tan(pi() / 4 + $fi / 2), 1 / $n)) - pi() / 2;
        $tga = pow((1 - $e1 * sin($fik)) / (1 + $e1 * sin($fik)), $n * $e1 / 2);
        $fi1Rad = 2 * atan(pow(1 / $k / $tga * tan(pi() / 4 + $fi / 2), 1 / $n)) - pi() / 2;
        $tga2 = pow((1 - $e1 * sin($fi1Rad)) / (1 + $e1 * sin($fi1Rad)), $n * $e1 / 2);
        $fi2Rad = 2 * atan(pow(1 / $k / $tga2 * tan(pi() / 4 + $fi / 2), 1 / $n)) - pi() / 2;
        $tga3 = pow((1 - $e1 * sin($fi2Rad)) / (1 + $e1 * sin($fi2Rad)), $n * $e1 / 2);
        $fi3Rad = 2 * atan(pow(1 / $k / $tga3 * tan(pi() / 4 + $fi / 2), 1 / $n)) - pi() / 2;
        $tga4 = pow((1 - $e1 * sin($fi3Rad)) / (1 + $e1 * sin($fi3Rad)), $n * $e1 / 2);
        $fi4Rad = 2 * atan(pow(1 / $k / $tga4 * tan(pi() / 4 + $fi / 2), 1 / $n)) - pi() / 2;
        $tga5 = pow((1 - $e1 * sin($fi4Rad)) / (1 + $e1 * sin($fi4Rad)), $n * $e1 / 2);
        $firad = 2 * atan(pow(1 / $k / $tga5 * tan(pi() / 4 + $fi / 2), 1 / $n)) - pi() / 2;
        $sjtskLat = $firad * 180 / pi();

        return [$sjtskLat, $sjtskLong];
    }

    protected function toWSG84LatLong(float $SJTSKlat, float $SJTSKlong): Interfaces\INumbers
    {
        $filRad = $SJTSKlat * pi() / 180;
        $la1Rad = $SJTSKlong * pi() / 180;
        $a1 = 6377397.155;
        $f1 = 0.00334277318217481;
        $a2 = 6378137.00;
        $f2 = 0.00335281066474748;
        $e1 = sqrt(2 * $f1 - $f1 * $f1);
        $M = $a1 * (1 - $e1 * $e1) / pow((1 - $e1 * $e1 * sin($filRad) * sin($filRad)), 1.5);
        $N = $a1 / sqrt(1 - $e1 * $e1 * sin($filRad) * sin($filRad));
        $dlasec = ((-1 * static::dX_SHIFT * sin($la1Rad)) + static::dY_SHIFT * cos($la1Rad)) / ($N * cos($filRad) * sin(pi() / 180 / 3600));
        $dfisec = ((-1 * static::dX_SHIFT * sin($filRad) * cos($la1Rad)) - static::dY_SHIFT * sin($filRad) * sin($la1Rad) + static::dZ_SHIFT * cos($filRad) + ($a1 * ($f2 - $f1) + $f1 * ($a2 - $a1)) * sin(2 * $filRad)) / ($M * sin(pi() / 180 / 3600));
        $wsgLong = $SJTSKlong + $dlasec / 3600;
        $wsgLat = $SJTSKlat + $dfisec / 3600;
        return (clone $this->pos)->setData($wsgLong, $wsgLat);
    }
}
