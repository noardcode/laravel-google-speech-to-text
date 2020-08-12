<?php

namespace Noardcode\SpeechToText\Transcripts;

use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\SpeechClient;

interface TranscriptInterface
{
    public function createTranscript(RecognitionConfig $config, RecognitionAudio $audio, SpeechClient $client): array;
}