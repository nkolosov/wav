<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\File;


use Wav\Exception\InvalidWavDataException;

class FormatSection
{
    const FMT = 'fmt ';

    const SECTION_SIZE = 16;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $audioFormat;

    /**
     * @var int
     */
    protected $numberOfChannels;

    /**
     * @var int
     */
    protected $sampleRate;

    /**
     * @var int
     */
    protected $byteRate;

    /**
     * @var int
     */
    protected $blockAlign;

    /**
     * @var int
     */
    protected $bitsPerSample;

    /**
     * @param array $data
     *
     * @return FormatSection
     */
    public static function createFromParameters(array $data)
    {
        $chunk = new FormatSection();

        $chunk->id = self::FMT;
        $chunk->size = self::SECTION_SIZE;
        $chunk->audioFormat = $data['audioFormat'];
        $chunk->numberOfChannels = $data['numberOfChannels'];
        $chunk->sampleRate = $data['sampleRate'];
        $chunk->byteRate = $data['byteRate'];
        $chunk->blockAlign = $data['blockAlign'];
        $chunk->bitsPerSample = $data['bitsPerSample'];

        return $chunk;
    }

    /**
     * @param array $data
     *
     * @return FormatSection
     * @throws InvalidWavDataException
     */
    public static function createFromArray(array $data)
    {
        $chunk = new FormatSection();

        $chunk->id = $data['id'];
        $chunk->size = $data['size'];
        $chunk->audioFormat = $data['audioFormat'];
        $chunk->numberOfChannels = $data['numberOfChannels'];
        $chunk->sampleRate = $data['sampleRate'];
        $chunk->byteRate = $data['byteRate'];
        $chunk->blockAlign = $data['blockAlign'];
        $chunk->bitsPerSample = $data['bitsPerSample'];

        if ($chunk->id !== self::FMT) {
            throw new InvalidWavDataException('Format section ID is not "fmt "');
        }

        return $chunk;
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
     * @return int
     */
    public function getAudioFormat()
    {
        return $this->audioFormat;
    }

    /**
     * @return int
     */
    public function getNumberOfChannels()
    {
        return $this->numberOfChannels;
    }

    /**
     * @return int
     */
    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    /**
     * @return int
     */
    public function getByteRate()
    {
        return $this->byteRate;
    }

    /**
     * @return int
     */
    public function getBlockAlign()
    {
        return $this->blockAlign;
    }

    /**
     * @return int
     */
    public function getBitsPerSample()
    {
        return $this->bitsPerSample;
    }
}