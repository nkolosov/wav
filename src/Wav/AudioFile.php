<?php

namespace Wav;

use Binary\Helper;
use Wav\Exception\DirectoryIsNotWritableException;

/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */
class AudioFile
{
    const HEADER_LENGTH = 44;

    const BITS_PER_BYTE = 8;

    /**
     * @var File\Header
     */
    protected $header;

    /**
     * @var File\FormatSection
     */
    protected $formatSection;

    /**
     * @var File\DataSection
     */
    protected $dataSection;

    /**
     * @var Sample[]
     */
    protected $samples = [];

    /**
     * AudioFile constructor.
     *
     * @param File\Header $header
     * @param File\FormatSection $formatSection
     * @param File\DataSection $dataSection
     */
    public function __construct(File\Header $header, File\FormatSection $formatSection, File\DataSection $dataSection)
    {
        $this->header        = $header;
        $this->formatSection = $formatSection;
        $this->dataSection   = $dataSection;
    }

    /**
     * @return Sample[]
     */
    public function getAsSamples()
    {
        if (!$this->samples) {
            $raw          = $this->dataSection->getRaw();

            $sampleSize   = $this->formatSection->getBitsPerSample() / self::BITS_PER_BYTE;
            $samplesCount = strlen($raw) / $this->formatSection->getNumberOfChannels();

            for ($i = 0; $i < $samplesCount; $i++) {
                $this->samples[] = new Sample($sampleSize, substr($raw, $i * $sampleSize, $sampleSize), $i % $this->formatSection->getNumberOfChannels());
            }
        }

        return $this->samples;
    }

    /**
     * @param string $filename
     *
     * @throws DirectoryIsNotWritableException
     */
    public function saveToFile($filename)
    {
        $directory = dirname($filename);

        if (!is_writable($directory)) {
            throw new DirectoryIsNotWritableException('Directory "' . $directory . '" is not writable');
        }

        $handle = fopen($filename, 'wb');

        $this->writeHeader($handle);
        $this->writeFormatSection($handle);
        $this->writeDataSection($handle);

        fclose($handle);
    }

    /**
     * @param resource $handle
     */
    protected function writeHeader($handle)
    {
        Helper::writeString($handle, $this->header->getId());
        Helper::writeLong($handle, $this->header->getSize());
        Helper::writeString($handle, $this->header->getFormat());
    }

    /**
     * @param resource $handle
     */
    protected function writeFormatSection($handle)
    {
        Helper::writeString($handle, $this->formatSection->getId());
        Helper::writeLong($handle, $this->formatSection->getSize());
        Helper::writeWord($handle, $this->formatSection->getAudioFormat());
        Helper::writeWord($handle, $this->formatSection->getNumberOfChannels());
        Helper::writeLong($handle, $this->formatSection->getSampleRate());
        Helper::writeLong($handle, $this->formatSection->getByteRate());
        Helper::writeWord($handle, $this->formatSection->getBlockAlign());
        Helper::writeWord($handle, $this->formatSection->getBitsPerSample());
    }

    /**
     * @param resource $handle
     */
    protected function writeDataSection($handle)
    {
        Helper::writeString($handle, $this->dataSection->getId());
        Helper::writeLong($handle, $this->dataSection->getSize());
        Helper::writeString($handle, $this->dataSection->getRaw());
    }

    /**
     * @return int
     */
    public function getAudioFormat()
    {
        return $this->formatSection->getAudioFormat();
    }

    /**
     * @return int
     */
    public function getNumberOfChannels()
    {
        return $this->formatSection->getNumberOfChannels();
    }

    /**
     * @return int
     */
    public function getSampleRate()
    {
        return $this->formatSection->getSampleRate();
    }

    /**
     * @return int
     */
    public function getByteRate()
    {
        return $this->formatSection->getByteRate();
    }

    /**
     * @return int
     */
    public function getBlockAlign()
    {
        return $this->formatSection->getBlockAlign();
    }

    /**
     * @return int
     */
    public function getBitsPerSample()
    {
        return $this->formatSection->getBitsPerSample();
    }
}