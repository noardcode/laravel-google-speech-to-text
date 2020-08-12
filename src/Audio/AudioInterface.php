<?php

namespace Noardcode\SpeechToText\Audio;

use Google\Cloud\Speech\V1\RecognitionAudio;

interface AudioInterface
{
    public function createRecognitionAudio(string $audioFile): RecognitionAudio;
}