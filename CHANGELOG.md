# Changelog

All notable changes to `speech to text` will be documented in this file

## 0.0.1 - 2019-06-10
- Initial release

## 0.0.2 - 2019-06-10
- Added support for Google Cloud Storage objects
- Split word time offset response into transcript and all words.

## 0.0.3 - 2019-06-29
- Added support for different implementations of an Audio file. 
- Added a FilesystemAudio and GoogleCloudStorage class.
- Added support for different types of an Transcripts. 
- Added a BasicTranscript and WordTimeOffsets class.

## 0.0.4 - 2019-07-01
- Resolved failed "mergeConfigFrom" exception.