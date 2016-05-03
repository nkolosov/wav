# WAV
Simple PHP Library for working with wav-files

# Installation
Installation using composer
    composer require nkolosov/wav

#Usage
Simple usage:
```php
    ...
    $file = Parser::fromFile('/path/to/file.wav');
    echo $file->getSampleRate(); //44100
    ...
```

You can create music file from code:
```php
    ...
    $sampleBuilder = new Piano(); //create piano sound generator

    //generate first notes of Für Elise (Ludwig van Beethoven)
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

    $builder = (new Builder())
         ->setAudioFormat(\Wav\WaveFormat::PCM)
         ->setNumberOfChannels(1)
         ->setSampleRate(\Wav\Builder::DEFAULT_SAMPLE_RATE)
         ->setByteRate(\Wav\Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
         ->setBlockAlign(1 * 16 / 8)
         ->setBitsPerSample(16)
         ->setSamples($samples);

    $audio = $builder->build();
    $audio->returnContent(); //return wav-file content directly to browser/console
    ...
```

