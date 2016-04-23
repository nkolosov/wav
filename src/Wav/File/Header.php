<?php

namespace Wav\File;

use Wav\Exception\InvalidWavDataException;

/**
 * Header structure of wav-file
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @package Wav\File
 */
class Header
{
    const RIFF = 'RIFF';
    const WAVE = 'WAVE';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $format;

    /**
     * @param DataSection $section
     *
     * @return Header
     */
    public static function createFromDataSection(DataSection $section)
    {
        $header = new Header();

        $header->id     = self::RIFF;
        $header->size   = 40 + $section->getSize();
        $header->format = self::WAVE;

        return $header;
    }

    /**
     * @param array $data
     *
     * @return Header
     * @throws InvalidWavDataException
     */
    public static function createFromArray(array $data)
    {
        $header = new Header();

        $header->id = $data['id'];
        $header->size = $data['size'];
        $header->format = $data['format'];

        if ($header->id !== self::RIFF) {
            throw new InvalidWavDataException('Header ID is not "RIFF"');
        }

        if ($header->format !== self::WAVE) {
            throw new InvalidWavDataException('Header format is not "WAVE"');
        }

        return $header;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
}