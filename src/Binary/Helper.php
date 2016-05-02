<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Binary;


class Helper
{
    /**
     * @param resource $handle
     * @param int $length
     *
     * @return string
     */
    public static function readString($handle, $length)
    {
        return self::readUnpacked($handle, 'a*', $length);
    }

    /**
     * @param resource $handle
     * @param mixed    $data
     *
     * @return int
     */
    public static function writeString($handle, $data)
    {
        return self::writeUnpacked($handle, 'a*', $data);
    }

    /**
     * @param resource $handle
     *
     * @return int
     */
    public static function readLong($handle)
    {
        return self::readUnpacked($handle, 'V', 4);
    }

    /**
     * @param resource $handle
     * @param mixed    $data
     *
     * @return int
     */
    public static function writeLong($handle, $data)
    {
        return self::writeUnpacked($handle, 'V', $data);
    }

    /**
     * @param resource $handle
     *
     * @return int
     */
    public static function readWord($handle)
    {
        return self::readUnpacked($handle, 'v', 2);
    }

    /**
     * @param resource $handle
     * @param mixed $data
     *
     * @return int
     */
    public static function writeWord($handle, $data)
    {
        return self::writeUnpacked($handle, 'v', $data);
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

    /**
     * @param resource $handle
     * @param string   $type
     * @param mixed    $data
     *
     * @return int
     */
    protected function writeUnpacked($handle, $type, $data)
    {
        return fwrite($handle, pack($type, $data));
    }
}