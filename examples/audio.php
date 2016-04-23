<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$filename = 'sample.wav';

$audio = \Wav\Parser::fromFile($filename);

$builder = (new \Wav\Builder())
    ->setAudioFormat($audio->getAudioFormat())
    ->setNumberOfChannels($audio->getNumberOfChannels())
    ->setSampleRate($audio->getSampleRate())
    ->setByteRate($audio->getByteRate())
    ->setBlockAlign($audio->getBlockAlign())
    ->setBitsPerSample($audio->getBitsPerSample())
    ->setSamples($audio->getSamples());

$audio = $builder->build();
$audio->saveToFile(__DIR__ . DIRECTORY_SEPARATOR . 'example.wav');


//header('Content-Type: audio/wav');
