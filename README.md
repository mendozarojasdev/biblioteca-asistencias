# Biblioteca Asistencias
Aplicación web implementada en la biblioteca del TecNM Campus San Juan del Río, permite registrar entradas de estudiantes, generar reportes y exportar datos a **Excel**.

## Tabla de contenido
- [Características principales](#características-principales)
- [Screenshots](#screenshots)
- [Tecnologías utilizadas](#tecnologías-utilizadas)
- [Requerimientos](#requerimientos)
- [Instalación](#instalación)
- [Licencia](#licencia)

## Screenshots

### Pantalla pública

![pantalla publica](screenshots/01-pantalla-publica.png)
> Pantalla pública

### Pantalla principal de administración

![pantalla publica](screenshots/02-pantalla-admin-principal.png)
> Pantalla de administración

### Pantalla de reportes

![pantalla publica](screenshots/03-pantalla-admin-reportes.png)
> Pantalla de reportes

📂 Puede ver más capturas en la carpeta [/screenshots](screenshots/).

## Tecnologías utilizadas

**Frontend**
- [Bootstrap 5](https://getbootstrap.com/)
- [jQuery](https://jquery.com/)

**Backend**
- PHP 8+
- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)

**Base de datos**
- MariaDB 10.5+

**Servidor**
- Apache 2.4

## Requerimientos
- PHP >= 8.0
- MariaDB >= 10.5
- Apache >= 2.4
- Composer

## Instalación

Puedes descargar la versión más reciente de Biblioteca Asistencias desde [GitHub Releases](https://github.com/mendozarojasdev/biblioteca-asistencias/releases/latest).

```bash
# Clonar repositorio
git clone https://github.com/usuario/proyecto-biblioteca.git

# Importar la base de datos
mysql -u root -p < database/schema.sql

# Colocar el proyecto en la carpeta htdocs de Apache
# Acceder desde: http://localhost/proyecto-biblioteca
```

## Licencia
Biblioteca Asistencias está publicado bajo la licencia MIT. Consulta el archivo [MIT license](https://github.com/mendozarojasdev/biblioteca-asistencias/blob/master/LICENSE) para más información.
