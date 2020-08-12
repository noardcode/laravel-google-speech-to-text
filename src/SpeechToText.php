<?php

namespace Noardcode\SpeechToText;

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Noardcode\SpeechToText\Audio\AudioInterface;
use Noardcode\SpeechToText\Transcripts\gRPC;
use Noardcode\SpeechToText\Transcripts\TranscriptInterface;

class SpeechToText
{
    /**
     * @var string
     */
    private $config;
    
    /**
     * @var string
     */
    private $client;
    
    /**
     * @var AudioInterface
     */
    private $audio;
    
    /**
     * @var TranscriptInterface
     */
    private $transcript;
    
    /**
     * SpeechToText constructor.
     *
     * @param  string  $pathToServiceAccountJson
     * @param  \Google\Cloud\Speech\V1\RecognitionConfig  $config
     * @param  \Noardcode\SpeechToText\Audio\AudioInterface  $audio
     * @param  \Noardcode\SpeechToText\Transcripts\TranscriptInterface  $transcript
     */
    public function __construct(
        string $pathToServiceAccountJson,
        RecognitionConfig $config,
        AudioInterface $audio,
        TranscriptInterface $transcript
    ) {
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.realpath($pathToServiceAccountJson));
        
        $this->config = $config;
        $this->audio = $audio;
        $this->transcript = $transcript;
    }
    
    /**
     * @param  string  $audioFile
     * @param  bool  $enableWordTimeOffsets
     *
     * @return array
     * @throws \Exception
     * @throws \Google\ApiCore\ValidationException
     */
    public function run(string $audioFile): array
    {
        if ($this->transcript instanceof gRPC) {
            // Check if required gRPC extension is loaded
            if (!extension_loaded('grpc')) {
                throw new \Exception('Install the grpc extension (pecl install grpc)');
            }
        }
        
        $recognitionAudio = $this->audio->createRecognitionAudio($audioFile);
        
        // instantiates a client
        $this->client = new SpeechClient();
        
        // retrieve transcript
        $transcript = $this->transcript->createTranscript($this->config, $recognitionAudio, $this->client);
        
        // close connection
        $this->client->close();
        
        return $transcript;
    }
    
    /**
     * @return \Google\Cloud\Speech\V1\RecognitionConfig
     */
    public function getConfig(): RecognitionConfig
    {
        return $this->config;
    }
    
    /**
     * @param  string  $languageCode
     *
     * @return \Noardcode\SpeechToText\SpeechToText
     */
    public function setLanguageCode(string $languageCode): SpeechToText
    {
        $this->config->setLanguageCode($languageCode);
        
        return $this;
    }
    
    /**
     * @param  string  $encoding
     *
     * @return \Noardcode\SpeechToText\SpeechToText
     */
    public function setEncoding(string $encoding): SpeechToText
    {
        $this->config->setEncoding($encoding);
        
        return $this;
    }
    
    /**
     * @param  string  $sampleRateHertz
     *
     * @return \Noardcode\SpeechToText\SpeechToText
     */
    public function setSampleRateHertz(string $sampleRateHertz): SpeechToText
    {
        $this->config->setSampleRateHertz($sampleRateHertz);
        
        return $this;
    }
    
    /**
     * @param  \Noardcode\SpeechToText\Audio\AudioInterface  $audio
     *
     * @return \Noardcode\SpeechToText\SpeechToText
     */
    public function setAudio(AudioInterface $audio): SpeechToText
    {
        $this->audio = $audio;
        
        return $this;
    }
    
    /**
     * @param  \Noardcode\SpeechToText\Transcripts\TranscriptInterface  $transcript
     *
     * @return \Noardcode\SpeechToText\SpeechToText
     */
    public function setTranscript(TranscriptInterface $transcript): SpeechToText
    {
        $this->transcript = $transcript;
        
        return $this;
    }
}