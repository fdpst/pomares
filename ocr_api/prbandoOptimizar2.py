# sys.path.append(r'A:\laragon\www\Python\ocr\ocr\Lib\site-packages')
import cv2
# from PIL import Image
# import pytesseract 

# from pytesseract import Output
import numpy as np
# import json

try:
    from PIL import Image
except ImportError:
    import Image
import pytesseract
from pytesseract import Output
import pandas as pd
pytesseract.pytesseract.tesseract_cmd =  r'C:\Program Files\Tesseract-OCR\tesseract'


# External libraries used for
# Image IO


# Morphological filtering
from skimage.morphology import opening
from skimage.morphology import disk

import os


# Connected component filtering
import cv2



doc = 'docs/originalfactura.png'
path = r'A:/laragon/www/APASE/ocr_api'
# im = Image.open(doc)


import datefinder
text = "uiuuiiuu 2010-07-10 love uhiuui07-June-2018hghju  banana"
matches = list(datefinder.find_dates(text))
if len(matches) > 0:   
# date returned will be a datetime.datetime object. here we are only using the first 
#match.
 print(matches)
else:
   print ('No dates found')