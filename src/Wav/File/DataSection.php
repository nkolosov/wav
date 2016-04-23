<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\File;


use Wav\Exception\InvalidWavDataException;

class DataSection extends Section
{
    const DATA = 'data';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int[]
     */
    protected $raw;

    /**
     * @param string $raw
     *
     * @return DataSection
     */
    public static function createFromRaw($raw)
    {
        $chunk = new DataSection();

        $chunk->id   = self::DATA;
        $chunk->size = strlen($raw);
        $chunk->raw  = $raw;

        return $chunk;
    }

    /**
     * @param array $data
     *
     * @return DataSection
     * @throws InvalidWavDataException
     */
    public static function createFromArray(array $data)
    {
        $chunk = new DataSection();

        $chunk->id   = $data['id'];
        $chunk->size = $data['size'];
        $chunk->raw  = $data['raw'];

        if ($chunk->id !== self::DATA) {
            throw new InvalidWavDataException('Data section ID is not "data"');
        }

        return $chunk;
    }

    /**
     * @return int[]
     */
    public function getRaw()
    {
        return $this->raw;
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
}