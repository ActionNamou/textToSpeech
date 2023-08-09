from PIL import Image
import numpy as np
import pytesseract
pytesseract.pytesseract.tesseract_cmd = r'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract'
filename = 'textToSpeech/image_01.png'
img1 = np.array(Image.open(filename))
text = pytesseract.image_to_string(img1)
print(text)


