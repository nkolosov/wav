<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$sampleBuilder = new \Wav\SampleBuilder(\Wav\Generator\AcousticGuitar::NAME);

$samples = [
    $sampleBuilder->note('E', 5, 0.3),
    $sampleBuilder->note('D#', 5, 0.3),
    $sampleBuilder->note('E', 5, 0.3),
    $sampleBuilder->note('D#', 5, 0.3),
    $sampleBuilder->note('E', 5, 0.3),
    $sampleBuilder->note('H', 4, 0.3),
    $sampleBuilder->note('D', 5, 0.3),
    $sampleBuilder->note('C', 5, 0.3),
    $sampleBuilder->note('A', 4, 1),
];

$builder = (new Wav\Builder())
    ->setAudioFormat(\Wav\WaveFormat::PCM)
    ->setNumberOfChannels(1)
    ->setSampleRate(\Wav\Builder::DEFAULT_SAMPLE_RATE)
    ->setByteRate(\Wav\Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
    ->setBlockAlign(1 * 16 / 8)
    ->setBitsPerSample(16)
    ->setSamples($samples);

$audio = $builder->build();
$audio->returnContent();