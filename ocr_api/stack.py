

# import sys
# sys.path.append(r'A:\laragon\www\Python\ocr\ocr\Lib\site-packages')
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




from xhtml2pdf import pisa
import os


archivo = open("A:/laragon/www/APASE/storage/app/public/documentos/procesarImagenes.txt")
líneas = archivo.readlines()
archivo.close()


path = ''
text = ''

#datos interes
date = ''

images = líneas #Nombres de imagenes escritos en el archivo procesarImagenes
cmas = 0
for image in images:

    #obtener imagen
    split = image.replace("\n", "")

    sacarPath = split.split("/")
    archivoSacar = len(sacarPath)-1
     
    for archivo in sacarPath:
        if sacarPath[archivoSacar] != archivo:
            path = path  + archivo + '/'
    
    

    image = split

   
    img = Image.open(image).convert("L")
    img.save(image, quality=95)
    #salvar imagen y dar calidad
    
    img_cv = ''
    for c in range(1,2):
    
        img_cv = cv2.imread(image)
        im = img_cv/255.0
        im_power_law_transformation = cv2.pow(im,2)

        cv2.imwrite(os.path.join(path , image), 255.0*im_power_law_transformation)
    
    img_cv = cv2.imread(image)
    #transformar a escala gris
    gray = cv2.cvtColor(img_cv, cv2.COLOR_BGR2GRAY)

    thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)[1]
    custom_config = r'--oem 3 --psm 6'
    d = pytesseract.image_to_data(thresh, config=custom_config, output_type=Output.DICT)



    df = pd.DataFrame(d)

    df1 = df[(df.conf != '-1') & (df.text != ' ') & (df.text != '')]
    pd.set_option('display.max_rows', None)
    pd.set_option('display.max_columns', None)

    
    sorted_blocks = df1.groupby('block_num').first().sort_values('top').index.tolist()
    for block in sorted_blocks:
        curr = df1[df1['block_num'] == block]

        sel = curr[curr.text.str.len() > 3]
        # sel = curr
        char_w = (sel.width / sel.text.str.len()).mean()
        prev_par, prev_line, prev_left = 0, 0, 0
        
        for ix, ln in curr.iterrows():
            # add new line when necessary
            if prev_par != ln['par_num']:
                text += '\n'
                prev_par = ln['par_num']
                prev_line = ln['line_num']
                prev_left = 0
            elif prev_line != ln['line_num']:
                text += '\n'
                prev_line = ln['line_num']
                prev_left = 0

            added = 0  # num of spaces that should be added
            if ln['left'] / char_w > prev_left + 1:
                added = int((ln['left']) / char_w) - prev_left
                text += ' ' * added
            text += ln['text'] + ' '
            prev_left += len(ln['text']) + added + 1
        text += '\n'




    
    
    splitPath = split.split("/")
    obtenerUser = ' '
    for x in splitPath:
        for t in range(1,10000):
            if 'userId_' + str(t) == x:
                obtenerUser = x
                break
        
    ROOT_PATH = os.path.dirname(os.path.abspath('../ocr_api'))
    #PATHPDF = ROOT_PATH + '/' + 'storage/app/public/documentos/' + obtenerUser + '/pdf-ocr' 
    PATHPDF = '../storage/app/public/documentos/' + obtenerUser + '/factura_recibidas/factura_'+  str(cmas)+'.pdf'

    # Define your data
    source_html = "<html><body><pre>" + text + "</pre></body></html>"
    output_filename =  PATHPDF
    
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


    cmas = cmas +1
    # print(text)

    import re

    date = print(re.search(r'[Date:]*([0-9]{0,2}[\/-]([0-9]{0,2}|[a-z]{3})[\/-][0-9]{0,4})', text).group(1))



#json json.dumps({"valores":[v1,v2]})

jsonO = {"data":[date, text]}

print(json.dumps(jsonO))