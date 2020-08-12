<?php

namespace Noardcode\SpeechToText\Transcripts;

use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\SpeechClient;

class WordTimeOffsets implements TranscriptInterface, gRPC
{
    /**
     * @param  \Google\Cloud\Speech\V1\RecognitionConfig  $config
     * @param  \Google\Cloud\Speech\V1\RecognitionAudio  $audio
     * @param  \Google\Cloud\Speech\V1\SpeechClient  $client
     *
     * @return array
     * @throws \Google\ApiCore\ApiException
     * @throws \Google\ApiCore\ValidationException
     */
    public function createTranscript(RecognitionConfig $config, RecognitionAudio $audio, SpeechClient $client): array
    {
        $config->setEnableWordTimeOffsets(true);
        
        // create the asynchronous recognize operation
        $operation = $client->longRunningRecognize($config, $audio);
        $operation->pollUntilComplete();
    
        if (!$operation->operationSucceeded()) {
            throw new \Exception($operation->getError());
        }
    
        $response = $operation->getResult();
    
        // each result is for a consecutive portion of the audio.
        // iterate through them to get the transcripts for the entire audio file.
        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
        
            $data = array();
            $data['transcript'] = $mostLikely->getTranscript();
            $data['confidence'] = $mostLikely->getConfidence();
        
            foreach ($mostLikely->getWords() as $wordInfo) {
                $startTime = ($wordInfo->getStartTime())->serializeToJsonString();
                $endTime = ($wordInfo->getEndTime())->serializeToJsonString();
            
                $word = [
                    'word' => $wordInfo->getWord(),
                    'startTime' => (float)str_replace(['"', "s"], '', $startTime),
                    'endTime' => (float)str_replace(['"', "s"], '', $endTime)
                ];
            
                $data['words'][] = $word;
                $allWords[] = $word;
            }
    
            $transcript[] = $data;
        }
    
        return [
            'transcript' => $transcript ?? [],
            'words' => $allWords ?? []
        ];
    }
}