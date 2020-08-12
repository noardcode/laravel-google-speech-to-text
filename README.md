# Laravel Google Speech to Text

This Laravel package provides a convenient interface for the Google Speech to Text API. 

## Prerequisites
* The gRPC packages is required when enabling the word time offsets option
    * Step 1: Run `pecl install grpc`
        * [gRPC PHP Quick Start](https://grpc.io/docs/quickstart/php/)
    * Step 2: Add `extension=grpc.so` to `php.ini`
        * `grpc.dll` on windows
        
## Getting started
* Open Google Cloud Console and add the `Cloud Speech-to-Text API` to `API's en services` in your project.
* Create an Google Service Account with the following role: Cloud Speech Service Agent
    * Make sure to generate a Service Account Key this file will be used for Authentication.
* Run `php artisan vendor:publish --provider="Noardcode\SpeechToText\SpeechToTextServiceProvider"`
    * This will create a `speech-to-text.php` file in your `config` folder.
* In `speech-to-text.php` change the following
```php
/*
|--------------------------------------------------------------------------
| Google Service Account
|--------------------------------------------------------------------------
*/
'service-account' => '/path/to/service-account.json',
``` 
> For a detailed documentation service accounts see: [https://cloud.google.com/docs/authentication/production](https://cloud.google.com/docs/authentication/production)
 
 ### Basic examples
 ```php
 // Run on Google Cloud Storage object
 resolve(SpeechToText::class)->run('gs://your-bucket-name/path-to-object');
 
 // Run on stored audio file (needs to be: less than 10MB in size and less than 1 minute in length)
 resolve(SpeechToText::class)
     ->setAudio(new FilesystemAudio)
     ->run('/path/to/audio-file');
 
 // Using different types of transcripts (e.g. include word time offsets (startTime and endTime))
 resolve(SpeechToText::class)->run('gs://your-bucket-name/path-to-object')
     ->setTranscript(new WordTimeOffsets)
     ->run('gs://your-bucket-name/path-to-object');
 ```
 
## Settings
You can change the default settings by publishing the config file and changing the following values.
```php
/*
|--------------------------------------------------------------------------
| Default parameters injected by the Service Provider
|--------------------------------------------------------------------------
*/
'defaults' => [
    'language' => 'en-US',
    'encoding' => \Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding::LINEAR16,
    'sampleRateHertz' => 44100
]
```
Or change the settings when an you have an instance of the class.
```php
$speechToText = resolve(SpeechToText::class)
    ->setLanguageCode('en-US')
    ->setEncoding(\Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding::LINEAR16)
    ->setSampleRateHertz(44100);
```

## Audio types
By default the SpeechToText class will be passed the a GoogleCloudStorageAudio class. This class tells the SpeechToText class how to create the RecognitionAudio class from the Google Speech to Text Package.
If you want to create the RecognitionAudio in in different way, e.g. a file from your local filesystem, you will need to set an other Audio class that implements the AudioInterface.
```php
// Run on audio file on local filesyem 
resolve(SpeechToText::class)
    ->setAudio(new FilesystemAudio)
    ->run('/path/to/audio-file');
```
> Side note: Google only supports sending inline files that are: less than 10MB in size and less than 1 minute in length

## Transcripts
By default the SpeechToText class will be passed the a BasicTranscript class. This class tells the SpeechToText class how to handle the response from the SpeechClient class from the Google Speech to Text Package.
If you want to handle the response from the SpeechClient in in different way, e.g. including the word time offsets, you will need to set an other Transcript class that implements the TranscriptInterface.
```php
// Using different types of transcripts (e.g. include word time offsets (startTime and endTime))
resolve(SpeechToText::class)->setTranscript(new WordTimeOffsets())
    ->run('gs://your-bucket-name/path-to-object');
```

#### Example output of WordTimeOffsets transcript
```
array:2 [
  'transcript' => array:10 [
      0 => array:3 [
        "transcript" => "hello world"
        "confidence" => 0.96761703491211
        "words" => array:9 [
          0 => array:3 [
            "word" => "hello"
            "startTime" => 0
            "endTime" => 0.3
          ]
          1 => array:3 [
            "word" => "world"
            "startTime" => 0.3
            "endTime" => 0.5
          ]
          ...
        ]
      ]
      1 => array:3 [
        "transcript" => "foo bar buz"
        "confidence" => 0.74065810441971
        "words" => array:7 [
            ...
        ]
      ]
  ]
  'words' => array:45 [
      0 => array:3 [
         "word" => "hello"
         "startTime" => 0
         "endTime" => 0.3
      ]
      1 => array:3 [
          "word" => "world"
          "startTime" => 0.3
          "endTime" => 0.5
      ]
      ...
  ]
]
```