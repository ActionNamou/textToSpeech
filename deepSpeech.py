def predict_deepspeech(audio_path, model):
  w = wave.open(audio_path, 'r')
  buffer = w.readframes(frames)

  data = np.frombuffer(buffer, dtype=np.int16)

  predicted_text = model.stt(data)

  return predicted_text