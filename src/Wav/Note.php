<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


class Note
{
    const C = 261.63;
    const C_SHARP = 277.18;
    const D = 293.66;
    const D_FLAT = self::C_SHARP;
    const D_SHARP = 311.13;
    const E = 329.63;
    const E_FLAT = self::D_SHARP;
    const E_SHARP = self::F;
    const F = 346.23;
    const F_FLAT = self::E;
    const F_SHARP = 369.99;
    const G = 392.00;
    const G_FLAT = self::F_SHARP;
    const G_SHARP = 415.30;
    const A = 440.00;
    const A_FLAT = self::G_SHARP;
    const A_SHARP = 466.16;
    const H = 493.88;
    const H_FLAT = self::A_SHARP;

    public static function get($note)
    {
        switch ($note) {
            case 'C':
                return self::C;
            case 'C#':
                return self::C_SHARP;
            case 'D':
                return self::D;
            case 'D#':
                return self::D_SHARP;
            case 'E':
                return self::E;
            case 'E#':
                return self::E_SHARP;
            case 'F':
                return self::F;
            case 'F#':
                return self::F_SHARP;
            case 'G':
                return self::G;
            case 'G#':
                return self::G_SHARP;
            case 'A':
                return self::A;
            case 'A#':
                return self::A_SHARP;
            case 'B':
                return self::H_FLAT;
            case 'H':
                return self::H;
        }
    }
}