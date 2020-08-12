<?php

namespace Noardcode\SpeechToText\Test;

use Illuminate\Support\Str;
use Noardcode\SpeechToText\SpeechToText;

class SpeechTest extends TestCase
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testWavFileToTranscript()
    {
        $speech = app()->make(SpeechToText::class);
        
        $transcript = $speech->run(__DIR__ . '/../resources/media/audio_small.wav');
        
        $this->assertFalse(empty($transcript), 'Transcript is empty');
        
        $this->assertTrue(Str::contains(
            $transcript[0], 'this battle depends the survival of Christian civilization'),
            'Output returns incorrect sentence'
        );
    }
    
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testWordTimeStamps()
    {
        $speech = app()->make(SpeechToText::class);
        
        $transcript = $speech->run(__DIR__ . '/../resources/media/audio_small.wav');
        
        $this->assertFalse(empty($transcript), 'Transcript is empty');
        
        $this->assertArrayHasKey('transcript', $transcript[0]);
        $this->assertArrayHasKey('confidence', $transcript[0]);
        
        $this->assertArrayHasKey('word', $transcript[0]['words'][0]);
        $this->assertArrayHasKey('startTime', $transcript[0]['words'][0]);
        $this->assertArrayHasKey('endTime', $transcript[0]['words'][0]);
    }
}