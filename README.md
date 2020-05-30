# test
Con esta prueba se espera dar a conocer la experiencia en desarrollo del aspirante a desarrollador para la empresa.

# Recursos para la prueba:
Servidor APACHE, Editor de Código y gestor de bases de datos.(MySql o MariaDB)

# PRUEBA

1. Debe crear una base de datos llamada prueba
2. Debe crear una tabla llamada usuarios con los siguientes campos
- Nombre :varchar
- Apellido:varchar
- Documento :varchar
3. Debe crear una tabla llamada productos con los siguientes campos
- Nombre del producto :varchar
- Total del producto :varchar
4. Debe crear una tabla llamada guía con los siguientes campos
- Numero_guia:varchar
- Descripción:varchar
- Id_producto:integer
 La tabla guía y productos debe estar enlazadas por una llave foránea
5. Debe realizar mínimo 3 registros en cada tabla.
6. Con PHP debe realizar un registro y un listado de las tablas.
7. Debe validar que al realizar un registro en la tabla de usuarios el documento sea único.
8. En la parte de hacer una guía, el número de guía debe ser único, debe tener una abreviatura
parecida a esta “P0001” y por mas registros que se hagan no puede pasar de 5 dígitos. (El
número de guía se debe generar automáticamente).
9. Realizar cualquier funcionalidad con Javascript o JQuery que interactúe con la base de datos
y con la pagina.
