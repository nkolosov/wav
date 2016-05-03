<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


use Wav\Exception\UnknownGenerator;
use Wav\Generator\AcousticGuitar;
use Wav\Generator\Generator;
use Wav\Generator\Organ;
use Wav\Generator\Piano;

class GeneratorFactory
{
    /**
     * @return Piano
     */
    public static function getPianoGenerator()
    {
        return new Piano();
    }

    /**
     * @return AcousticGuitar
     */
    public static function getAcousticGuitarGenerator()
    {
        return new AcousticGuitar();
    }

    /**
     * @return Organ
     */
    public static function getOrganGenerator()
    {
        return new Organ();
    }

    /**
     * @param $name
     *
     * @return Generator
     * @throws UnknownGenerator
     */
    public static function getGenerator($name)
    {
        switch ($name) {
            case Piano::NAME:
                return self::getPianoGenerator();
            case AcousticGuitar::NAME:
                return self::getAcousticGuitarGenerator();
            case Organ::NAME:
                return self::getOrganGenerator();
        }

        throw new UnknownGenerator('Unknown generator "' . $name . '"');
    }
}