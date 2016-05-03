<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;


class Piano extends Generator
{
    /**
     * @var string Name of piano generator
     */
    const NAME = 'piano';

    /**
     * @var float Attack for piano generator
     */
    const ATTACK = 0.002;

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param number $sampleRate
     * @param number $frequency
     * @param number $volume
     *
     * @return float
     */
    public function getAttack($sampleRate = null, $frequency = null, $volume = null)
    {
        return self::ATTACK;
    }

    /**
     * @param number $sampleRate
     * @param number $frequency
     * @param number $volume
     *
     * @return number
     */
    public function getDampen($sampleRate = null, $frequency = null, $volume = null)
    {
        return pow(0.5 * log(($frequency * $volume) / $sampleRate), 2);
    }

    /**
     * @param number $sampleRate
     * @param number $frequency
     * @param number $volume
     * @param number $i
     *
     * @return mixed
     */
    public function getWave($sampleRate, $frequency, $volume, $i)
    {
        $base = $this->getModulations()[0];

        return call_user_func_array($base, [
            $i,
            $sampleRate,
            $frequency,
            pow(call_user_func_array($base, [$i, $sampleRate, $frequency, 0]), 2) +
            0.75 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.25]) +
            0.1 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.5])
        ]);
    }
}