

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

pytesseract.pytesseract.tesseract_cmd =  r'C:\Program Files\Tesseract-OCR\tesseract'




archivo = open("A:/laragon/www/APASE/storage/app/public/documentos/userId_1/ocr/ruta.txt")
líneas = archivo.readlines()

image = líneas[0]

img_cv = cv2.imread(image)
archivo.close()

# img_rgb = cv2.cvtColor(img_cv, cv2.COLOR_BGR2RGB)
# print(pytesseract.image_to_osd(img_rgb))



img = Image.open(image)
img.save('image.png', dpi=(1000,1000))


custom_oem_psm_config = r'--oem 3 --psm 6'
text = pytesseract.image_to_string(img_cv, config=custom_oem_psm_config)

from xhtml2pdf import pisa
import os


# Define your data
source_html = "<html><body><pre>" + text + "</pre></body></html>"
output_filename = "test.pdf"

# Utility function
def convert_html_to_pdf(source_html, output_filename):
    # open output file for writing (truncated binary)
    result_file = open(output_filename, "w+b")

    # convert HTML to PDF
    pisa_status = pisa.CreatePDF(
            source_html,                # the HTML to convert
            dest=result_file)           # file handle to recieve result

    # close output file
    result_file.close()                 # close output file

    # return False on success and True on errors
    return pisa_status.err

# Main program
if __name__ == "__main__":
    pisa.showLogging()
    convert_html_to_pdf(source_html, output_filename)



print(text)