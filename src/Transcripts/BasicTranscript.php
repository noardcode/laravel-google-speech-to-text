<?php

namespace Noardcode\SpeechToText\Transcripts;

use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\SpeechClient;

class BasicTranscript implements TranscriptInterface
{
    /**
     * @param  \Google\Cloud\Speech\V1\RecognitionConfig  $config
     * @param  \Google\Cloud\Speech\V1\RecognitionAudio  $audio
     * @param  \Google\Cloud\Speech\V1\SpeechClient  $client
     *
     * @return array
     * @throws \Google\ApiCore\ApiException
     */
    public function createTranscript(RecognitionConfig $config, RecognitionAudio $audio, SpeechClient $client): array
    {
        // detects speech in the audio file
        $response = $client->recognize($config, $audio);
    
        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
        
            $transcript[] = $mostLikely->getTranscript();
        }
    
        return $transcript ?? [];
    }
}