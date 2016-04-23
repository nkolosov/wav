<?php

namespace Wav;

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
    public function getSamples()
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
        fwrite($handle, pack('a*', $this->header->getId()));
        fwrite($handle, pack('V', $this->header->getSize()));
        fwrite($handle, pack('a*', $this->header->getFormat()));
    }

    /**
     * @param resource $handle
     */
    protected function writeFormatSection($handle)
    {
        fwrite($handle, pack('a*', $this->formatSection->getId()));
        fwrite($handle, pack('V', $this->formatSection->getSize()));
        fwrite($handle, pack('v', $this->formatSection->getAudioFormat()));
        fwrite($handle, pack('v', $this->formatSection->getNumberOfChannels()));
        fwrite($handle, pack('V', $this->formatSection->getSampleRate()));
        fwrite($handle, pack('V', $this->formatSection->getByteRate()));
        fwrite($handle, pack('v', $this->formatSection->getBlockAlign()));
        fwrite($handle, pack('v', $this->formatSection->getBitsPerSample()));
    }

    /**
     * @param resource $handle
     */
    protected function writeDataSection($handle)
    {
        fwrite($handle, pack('a*', $this->dataSection->getId()));
        fwrite($handle, pack('V', $this->dataSection->getSize()));
        
        foreach ($this->getSamples() as $sample) {
            fwrite($handle, $sample->getData());
        }
        
        fwrite($handle, $this->dataSection->getRaw());
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