<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


class Sample
{
    /**
     * @var int
     */
    protected $sampleSize;

    /**
     * @var int
     */
    protected $channel;

    /**
     * @var string
     */
    protected $data;

    /**
     * Sample constructor.
     *
     * @param int    $sampleSize
     * @param string $data
     * @param int    $channel
     */
    public function __construct($sampleSize, $data, $channel = 1)
    {
        $this->sampleSize = $sampleSize;
        $this->data       = (string) $data;
        $this->channel    = $channel;
    }

    /**
     * @return int
     */
    public function getSampleSize()
    {
        return $this->sampleSize;
    }

    /**
     * @return int
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}