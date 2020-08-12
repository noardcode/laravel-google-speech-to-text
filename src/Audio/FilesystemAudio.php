<?php

namespace Noardcode\SpeechToText\Audio;

use Google\Cloud\Speech\V1\RecognitionAudio;

class FilesystemAudio implements AudioInterface
{
    /**
     * @param  string  $audioFile
     *
     * @return \Google\Cloud\Speech\V1\RecognitionAudio
     */
    public function createRecognitionAudio(string $audioFile): RecognitionAudio
    {
        return (new RecognitionAudio())
            ->setContent(file_get_contents($audioFile));
    }
}