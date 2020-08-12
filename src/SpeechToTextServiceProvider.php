<?php

namespace Noardcode\SpeechToText;

use Google\Cloud\Speech\V1\RecognitionConfig;
use Illuminate\Support\ServiceProvider;
use Noardcode\SpeechToText\Audio\GoogleCloudStorageAudio;
use Noardcode\SpeechToText\SpeechToText;
use Noardcode\SpeechToText\Transcripts\BasicTranscript;

class SpeechToTextServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/speech-to-text.php';
    
        $this->publishes([$configPath => config_path('speech-to-text.php')]);
    
        $this->mergeConfigFrom($configPath, 'speech-to-text');
        
        app()->bind(SpeechToText::class, function() {
            $config = (new RecognitionConfig())
                ->setEncoding(config('speech-to-text.defaults.encoding'))
                ->setSampleRateHertz(config('speech-to-text.defaults.sampleRateHertz'))
                ->setLanguageCode(config('speech-to-text.defaults.language'));
            
            return new SpeechToText(
                config('speech-to-text.service-account'),
                $config,
                resolve(GoogleCloudStorageAudio::class),
                resolve(BasicTranscript::class)
            );
        });
    }
}