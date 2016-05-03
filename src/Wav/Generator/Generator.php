<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;


abstract class Generator
{
    /**
     * @return Callable[]
     */
    protected function getModulations()
    {
        return [
            function($i, $sampleRate, $frequency, $x) {
                return 1 * sin(2 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 1 * sin(4 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 1 * sin(8 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 1 * sin(0.5 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 1 * sin(0.25 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 0.5 * sin(2 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 0.5 * sin(4 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 0.5 * sin(8 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 0.5 * sin(0.5 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function($i, $sampleRate, $frequency, $x) {
                return 0.5 * sin(0.25 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
        ];
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param number $sampleRate
     * @param number $frequency
     * @param number $volume
     *
     * @return number
     */
    abstract public function getAttack($sampleRate = null, $frequency = null, $volume = null);

    /**
     * @param number $sampleRate
     * @param number $frequency
     * @param number $volume
     *
     * @return number
     */
    abstract public function getDampen($sampleRate = null, $frequency = null, $volume = null);

    /**
     * @param number $sampleRate
     * @param number$frequency
     * @param number $volume
     * @param number $i
     *
     * @return number
     */
    abstract public function getWave($sampleRate, $frequency, $volume, $i);
}