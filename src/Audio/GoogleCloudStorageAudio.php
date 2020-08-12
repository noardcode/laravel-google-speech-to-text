<?php

namespace Noardcode\SpeechToText\Audio;

use Google\Cloud\Speech\V1\RecognitionAudio;

class GoogleCloudStorageAudio implements AudioInterface
{
    /**
     * @param  string  $audioFile
     *
     * @return \Google\Cloud\Speech\V1\RecognitionAudio
     */
    public function createRecognitionAudio(string $audioFile): RecognitionAudio
    {
        return (new RecognitionAudio())
            ->setUri($audioFile);
    }
}