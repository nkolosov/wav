<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;


class Organ extends Generator
{
    const NAME = 'organ';

    const ATTACK = 0.3;

    public function getName()
    {
        return self::NAME;
    }

    public function getAttack($sampleRate = null, $frequency = null, $volume = null)
    {
        return self::ATTACK;
    }

    public function getDampen($sampleRate = null, $frequency = null, $volume = null)
    {
        return 1 + ($frequency * 0.01);
    }

    public function getWave($sampleRate, $frequency, $volume, $i)
    {
        $base = $this->getModulations()[0];

        return call_user_func_array($base, [
            $i,
            $sampleRate,
            $frequency,
            call_user_func_array($base, [$i, $sampleRate, $frequency, 0]) +
            0.5 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.25]) +
            0.25 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.5])
        ]);
    }

}