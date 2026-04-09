from PIL import Image, ImageFilter


# image = Image.open('docs/f2.png')
foto = Image.open('docs/f2.png').convert('L')

coeficientes = [1, 1, 1, 1, -8, 1, 1, 1, 1]
datos_laplace = foto.filter(ImageFilter.Kernel((3,3), coeficientes,1)).getdata()
datos_imagen = foto.getdata()
print(datos_imagen)


w = 1 / 3
datos_nitidez = [datos_imagen[x] - (w * datos_laplace[x]) for x in range(len(datos_laplace))]
imagen_nitidez = Image.new('L', foto.size)
imagen_nitidez.putdata(datos_nitidez)
imagen_nitidez.save('gray.png')
 
foto.close()
imagen_nitidez.close()



from PIL import Image, ImageEnhance

image = Image.open('gray.png')
print(image)
contrast = ImageEnhance.Contrast(image)
contrast.enhance(1.5).save('contrast.png')

