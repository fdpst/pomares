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

black = 0
white = 255
threshold = 160

# Open input image in grayscale mode and get its pixels.

doc = 'docs/12.jpg'
path = r'A:/laragon/www/APASE/ocr_api'

# im = Image.open(doc)
# rgb_im = im.convert('RGB')

# width, height = im.size
 
# for w in range(width):
#     for h in range(height):
#         print(rgb_im.getpixel((w,h)))

im = cv2.imread(doc)
im = im/255.0
im_power_law_transformation = cv2.pow(im,2)

cv2.imshow('im', im_power_law_transformation)
cv2.waitKey()


cv2.imwrite(os.path.join(path , 'newImg.png'), 255.0*im_power_law_transformation)
