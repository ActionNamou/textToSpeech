# import whisper



# model = whisper.load_model("base")
# audio = "C:\\Users\\Ng\\Desktop\\Hometown.mp3"
# result = model.transcribe(audio)
# output_directory = "C:\\Users\\Ng\\Desktop\\Hometown2.txt"

# # # load audio and pad/trim it to fit 30 seconds
# # audio = whisper.load_audio("C:\\Users\\Ng\\Desktop\\Hometown.mp3")
# # #audio = whisper.pad_or_trim(audio)
# # # make log-Mel spectrogram and move to the same device as the model
# # mel = whisper.log_mel_spectrogram(audio).to(model.device)

# # # detect the spoken language
# # _, probs = model.detect_language(mel)
# # print(f"Detected language: {max(probs, key=probs.get)}")

# # # decode the audio
# # options = whisper.DecodingOptions(fp16 = False)
# # result2 = whisper.decode(model, mel, options)


# # Save as a TXT file without any line breaks
# with open("C:\\Users\\Ng\\Desktop\\Hometown.txt", "w", encoding="utf-8") as txt:
#     txt.write(result["text"])


# # Save as a TXT file with hard line breaks
# txt_writer = get_writer("txt", output_directory)
# txt_writer(result, audio)



import whisper
audio = "C:\\Users\\Ng\\Desktop\\Hometown.mp3"
model = whisper.load_model("base")
result = model.transcribe(audio)
print(result["text"])