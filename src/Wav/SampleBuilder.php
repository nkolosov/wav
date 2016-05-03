<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


use Wav\Generator\Generator;

class SampleBuilder
{
    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var number
     */
    protected $sampleRate = Builder::DEFAULT_SAMPLE_RATE;

    /**
     * @var number
     */
    protected $volume = Builder::DEFAULT_VOLUME;

    /**
     * SampleBuilder constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->generator = GeneratorFactory::getGenerator($name);
    }

    /**
     * @return Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @param Generator $generator
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return int
     */
    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    /**
     * @param int $sampleRate
     */
    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;
    }

    /**
     * @return number
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param number $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * @param string $note
     * @param int    $octave
     * @param number $duration
     *
     * @return Sample
     */
    public function note($note, $octave, $duration)
    {
        $result = new \SplFixedArray((int) ceil($this->getSampleRate() * $duration * 2));

        $octave = min(8, max(1, $octave));

        $frequency = Note::get($note) * pow(2, $octave - 4);

        $attack = $this->generator->getAttack($this->getSampleRate(), $frequency, $this->getVolume());
        $dampen = $this->generator->getDampen($this->getSampleRate(), $frequency, $this->getVolume());

        $attackLength = (int) ($this->getSampleRate() * $attack);
        $decayLength  = (int) ($this->getSampleRate() * $duration);

        for ($i = 0; $i < $attackLength; $i++) {
            $value = $this->getVolume()
                * ($i / ($this->getSampleRate() * $attack))
                * $this->getGenerator()->getWave(
                        $this->getSampleRate(),
                        $frequency,
                        $this->getVolume(),
                        $i
                );

            $result[$i << 1]       = pack('c', $value);
            $result[($i << 1) + 1] = pack('c', $value >> 8);
        }

        for (; $i < $decayLength; $i++) {
            $value = $this->getVolume()
                * pow((1 - (($i - ($this->getSampleRate() * $attack)) / ($this->getSampleRate() * ($duration - $attack)))), $dampen)
                * $this->getGenerator()->getWave(
                        $this->getSampleRate(),
                        $frequency,
                        $this->getVolume(),
                        $i
                );

            $result[$i << 1]       = pack('c', $value);
            $result[($i << 1) + 1] = pack('c', $value >> 8);
        }

        return new Sample($result->getSize(), implode('', $result->toArray()));
    }
}