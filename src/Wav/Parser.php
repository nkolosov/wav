<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


use Binary\Helper;
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
            'id'     => Helper::readString($handle, 4),
            'size'   => Helper::readLong($handle),
            'format' => Helper::readString($handle, 4),
        ];
    }

    /**
     * @param resource $handle
     * @return array
     */
    protected static function parseFormatSection($handle)
    {
        return [
            'id'               => Helper::readString($handle, 4),
            'size'             => Helper::readLong($handle),
            'audioFormat'      => Helper::readWord($handle),
            'numberOfChannels' => Helper::readWord($handle),
            'sampleRate'       => Helper::readLong($handle),
            'byteRate'         => Helper::readLong($handle),
            'blockAlign'       => Helper::readWord($handle),
            'bitsPerSample'    => Helper::readWord($handle),
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
            'id' => Helper::readString($handle, 4),
            'size' => Helper::readLong($handle),
        ];

        if ($data['size'] > 0) {
            $data['raw'] = fread($handle, $data['size']);
        }

        return $data;
    }
}