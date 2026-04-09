
import sys
sys.path.append(r'A:\laragon\www\Python\ocr\ocr\Lib\site-packages')
import cv2
# from PIL import Image
# import pytesseract 

# from pytesseract import Output
import numpy as np
import json

try:
    from PIL import Image
except ImportError:
    import Image
import pytesseract
from pytesseract import Output
import pandas as pd
pytesseract.pytesseract.tesseract_cmd =  r'C:\Program Files\Tesseract-OCR\tesseract'


archivo = open("A:/laragon/www/APASE/ocr_api/docs/ruta.txt")
líneas = archivo.readlines()

image = líneas[0]


img_cv = cv2.imread(image)
gray = cv2.cvtColor(img_cv, cv2.COLOR_BGR2GRAY)
thresh = cv2.threshold(gray, 127, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)[1]


custom_config = r'--oem 3 --psm 6'
d = pytesseract.image_to_data(thresh, config=custom_config, output_type=Output.DICT)


print(d)