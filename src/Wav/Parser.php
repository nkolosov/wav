<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


use Wav\Exception\FileIsNotExistsException;
use Wav\Exception\FileIsNotReadableException;
use Wav\Exception\FileIsNotWavFileException;
use Wav\File\DataSection;
use Wav\File\FormatSection;
use Wav\File\Header;


class Parser
{
    /**
     * @param string $filename path to wav-file
     *
     * @return AudioFile
     * @throws FileIsNotExistsException
     * @throws FileIsNotReadableException
     * @throws FileIsNotWavFileException
     */
    public static function fromFile($filename)
    {
        if (!file_exists($filename)) {
            throw new FileIsNotExistsException('File "' . $filename . '" is not exists.');
        }

        if (!is_readable($filename)) {
           throw new FileIsNotReadableException('File "' . $filename . '" is not readable"');
        }

        if (is_dir($filename)) {
            throw new FileIsNotWavFileException('File "' . $filename . '" is not a wav-file');
        }

        $size = filesize($filename);
        if ($size < AudioFile::HEADER_LENGTH) {
            throw new FileIsNotWavFileException('File "' . $filename . '" is not a wav-file');
        }
        
        $handle = fopen($filename, 'rb');

        try {
            $header         = Header::createFromArray(self::parseHeader($handle));
            $formatSection  = FormatSection::createFromArray(self::parseFormatSection($handle));
            $dataSection    = DataSection::createFromArray(self::parseDataSection($handle));
        } finally {
            fclose($handle);
        }

        return new AudioFile($header, $formatSection, $dataSection);
    }

    /**
     * @param resource $handle
     * @return array
     */
    protected static function parseHeader($handle)
    {
        return [
            'id' => self::readString($handle, 4),
            'size' => self::readLong($handle),
            'format' => self::readString($handle, 4),
        ];
    }

    /**
     * @param resource $handle
     * @return array
     */
    protected static function parseFormatSection($handle)
    {
        return [
            'id' => self::readString($handle, 4),
            'size' => self::readLong($handle),
            'audioFormat' => self::readWord($handle),
            'numberOfChannels' => self::readWord($handle),
            'sampleRate' => self::readLong($handle),
            'byteRate' => self::readLong($handle),
            'blockAlign' => self::readWord($handle),
            'bitsPerSample' => self::readWord($handle),
        ];
    }

    /**
     * @param resource $handle
     *
     * @return array
     */
    protected static function parseDataSection($handle)
    {
        $data = [
            'id' => self::readString($handle, 4),
            'size' => self::readLong($handle),
        ];

        if ($data['size'] > 0) {
            $data['raw'] = fread($handle, $data['size']);
        }

        return $data;
    }

    /**
     * @param resource $handle
     * @param int      $length
     *
     * @return string
     */
    protected static function readString($handle, $length)
    {
        return self::readUnpacked($handle, 'a*', $length);
    }

    /**
     * @param resource $handle
     *
     * @return int
     */
    protected static function readLong($handle)
    {
        return self::readUnpacked($handle, 'V', 4);
    }

    /**
     * @param resource $handle
     *
     * @return int
     */
    protected static function readWord($handle)
    {
        return self::readUnpacked($handle, 'v', 2);
    }

    /**
     * @param resource $handle
     * @param string   $type
     * @param int      $length
     *
     * @return mixed
     */
    protected function readUnpacked($handle, $type, $length)
    {
        $data = unpack($type, fread($handle, $length));

        return array_pop($data);
    }


}