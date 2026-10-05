# 🔧 TechFix — Portal Server-Side de Gestión de Reparaciones

TechFix es una aplicación web desarrollada en **PHP** para gestionar órdenes de reparación, presupuestos y un catálogo de recambios.

El proyecto ha sido realizado como actividad integradora del módulo **Desarrollo Web en Entorno Servidor (DWES)** del ciclo formativo de **Desarrollo de Aplicaciones Web (DAW)**.

Su objetivo principal es aplicar de forma práctica conceptos de PHP relacionados con peticiones HTTP, tipado estricto, programación orientada a objetos, colecciones, paginación, búfer de salida y renderizado seguro de contenido.

---

## 🚀 Funcionalidades

La aplicación permite:

- Recibir y validar parámetros mediante peticiones HTTP `GET`.
- Validar números de solicitud y responder con códigos HTTP apropiados.
- Procesar nombres con caracteres multibyte mediante funciones `mb_*`.
- Gestionar distintos tipos de reparación mediante un `enum`.
- Representar órdenes de trabajo mediante una clase inmutable.
- Calcular presupuestos utilizando funciones fuertemente tipadas.
- Gestionar un catálogo de recambios.
- Aplicar una tasa de almacenamiento sobre los precios.
- Filtrar productos según su stock disponible.
- Calcular el valor total del inventario.
- Paginar el catálogo de productos.
- Generar la vista HTML mediante búfer de salida.
- Escapar los datos mostrados en la interfaz para prevenir ataques XSS.

---

## 🧠 Conceptos aplicados

### Configuración y HTTP

- `declare(strict_types=1)`
- `$_GET`
- `filter_var()`
- `FILTER_VALIDATE_INT`
- `http_response_code()`
- `exit()`
- Zona horaria con `date_default_timezone_set()`

### Procesamiento de cadenas

- `trim()`
- `mb_strtoupper()`
- `mb_strlen()`
- Codificación UTF-8

### Programación Orientada a Objetos

- Clases
- Clase `readonly`
- Backed enums
- Tipado de propiedades
- Funciones fuertemente tipadas
- Argumentos nombrados
- Excepciones con `InvalidArgumentException`

### Arrays y colecciones

- Arrays asociativos
- `foreach`
- Referencias mediante `&`
- `unset()`
- `array_filter()`
- `array_slice()`
- `array_values()`
- Cálculo de sumatorios

### Renderizado y seguridad

- `ob_start()`
- `ob_get_clean()`
- Sintaxis alternativa de PHP
- `htmlspecialchars()`
- Prevención de vulnerabilidades XSS

---

## 📁 Estructura del proyecto

```text
techfix/
│
├── config.php
├── index.php
├── OrdenTrabajo.php
└── README.md
```

### `config.php`

Contiene la configuración general de PHP, incluyendo el tipado estricto, la zona horaria, la configuración de errores y funciones auxiliares utilizadas para generar una salida HTML segura.

### `OrdenTrabajo.php`

Contiene la lógica orientada a objetos del proyecto:

- `TipoReparacion`
- clase `OrdenTrabajo`
- función de cálculo del presupuesto

### `index.php`

Es el punto principal de entrada de la aplicación.

Se encarga de:

- procesar los parámetros HTTP;
- validar los datos recibidos;
- gestionar el catálogo;
- calcular el inventario;
- realizar la paginación;
- crear la orden de trabajo;
- y generar la interfaz HTML.

---

## 🌐 Ejemplo de uso

Con el proyecto ubicado dentro de `htdocs` de XAMPP y Apache iniciado, puede accederse desde:

```text
http://localhost/techfix/?solicitud=15&cliente=Maria&pagina=1
```

Los principales parámetros son:

| Parámetro | Descripción | Ejemplo |
|---|---|---|
| `solicitud` | Número de la solicitud de reparación | `15` |
| `cliente` | Nombre del cliente | `Maria` |
| `pagina` | Página del catálogo que se desea visualizar | `1` |

Si no se proporciona un cliente, la aplicación utiliza:

```text
Cliente Anónimo
```

Si el número de solicitud no es válido, el servidor devuelve una respuesta:

```text
400 Bad Request
```

---

## 🔒 Seguridad

Los datos que terminan formando parte de la interfaz HTML son escapados mediante:

```php
htmlspecialchars()
```

De esta manera se evita que contenido introducido por el usuario sea interpretado directamente como HTML o JavaScript, reduciendo el riesgo de ataques **Cross-Site Scripting (XSS)**.

---

## 🛠️ Tecnologías utilizadas

- PHP 8
- HTML5
- CSS3
- Apache
- XAMPP
- Git
- GitHub
- Visual Studio Code

---

## ▶️ Ejecución local

### 1. Clonar el repositorio

```bash
git clone https://github.com/rdominguez-369/techfix.git
```

### 2. Colocar el proyecto

Si se utiliza XAMPP en Windows, el proyecto debe encontrarse dentro de:

```text
C:\xampp\htdocs\techfix
```

### 3. Iniciar Apache

Abrir **XAMPP Control Panel** e iniciar el módulo Apache.

### 4. Abrir TechFix

En el navegador:

```text
http://localhost/techfix/?solicitud=15&cliente=Maria&pagina=1
```

---

## 🧪 Comprobación de sintaxis

La sintaxis de los archivos PHP puede comprobarse desde terminal mediante:

```bash
php -l config.php
php -l OrdenTrabajo.php
php -l index.php
```

PHP debería indicar que no existen errores de sintaxis en los archivos.

---

## 🎓 Contexto académico

Proyecto desarrollado como práctica de **Desarrollo Web en Entorno Servidor (DWES)**.

La actividad integra contenidos relacionados con:

1. Configuración del entorno servidor y validación HTTP.
2. Programación Orientada a Objetos y estructuras avanzadas.
3. Gestión de colecciones y paginación.
4. Búfer de salida y renderizado seguro.
5. Control de versiones mediante Git y GitHub.

---

## 👨‍💻 Autor

**Renzo Domínguez**

Estudiante de Desarrollo de Aplicaciones Web (DAW).
