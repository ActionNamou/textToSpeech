# `pip3 install assemblyai` (macOS)
# `pip install assemblyai` (Windows)

import assemblyai as aai

aai.settings.api_key = "4a920130b6224e369e93fd1ef57c7794"
transcriber = aai.Transcriber()
audio = "recording.mp3"
transcript = transcriber.transcribe(audio)
# transcript = transcriber.transcribe("./my-local-audio-file.wav")

print(transcript.text)