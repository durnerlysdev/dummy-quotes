<p align="center">
    <a href="./README.md">Inglés / English</a>
</p>

# Dummy Quotes

Este paquete de Laravel proporciona una interfaz de usuario construida con Vue.js para interactuar con la API de [DummyJSON Quotes](https://dummyjson.com/quotes). Ofrece funcionalidades como la obtención de todas las citas, citas aleatorias y citas específicas por ID, implementando además limitación de tasa de solicitudes y caché local eficiente con búsqueda binaria.

## Instalación

Puedes instalar este paquete a través de Composer:

```bash
composer require durnerlys/dummy-quotes
```

## Publicación de Assets

Para publicar los assets del proveedor, puedes utilizar los siguientes comandos de Artisan. Se recomienda publicar todos los assets para asegurarte que todo esté disponible en tu aplicación.

**Publicar todos los assets del proveedor (recomendado):**

```bash
php artisan vendor:publish --provider="Durnerlys\DummyQuotes\DummyQuotesServiceProvider"
```

**O, si prefieres publicar por etiquetas específicas:**

```bash
#Rutas de la API
php artisan vendor:publish --tag="dummy-quotes-routes"

#Vista Blade (Entrada a las vistas de Vue)
php artisan vendor:publish --tag="dummy-quotes-views"

#Fuentes de Vue.js (carpeta 'resources/js')
php artisan vendor:publish --tag="dummy-quotes-vue-sources"

#Assets compilados (carpeta 'dist' de Vue)
php artisan vendor:publish --tag="dummy-quotes-assets"
```

## Dependencias

Este paquete utiliza Vue.js para sus componentes de interfaz de usuario. Para asegurar la correcta funcionalidad, la aplicación Laravel que consuma este paquete necesita tener instaladas las siguientes dependencias:

**Dependencias de Vue:**

-   `vue`: (Versión mínima recomendada: `^3.5.13` o la versión utilizada por este paquete). Necesario para renderizar los componentes Vue proporcionados por este paquete.

    ```bash
    npm install vue@^3.5.13
    # o
    yarn add vue@^3.5.13
    ```

-   `vue-router`: (Versión mínima recomendada: `^4.5.1` o la versión utilizada por este paquete). Requerido porque este paquete utiliza el enrutamiento de Vue dentro de sus componentes.

    ```bash
    npm install vue-router@^4.5.1
    # o
    yarn add vue-router@^4.5.1
    ```

**Otras dependencias de CSS/UI:**

-   `bootstrap`: (Versión mínima recomendada: `^5.3.5` o la versión utilizada por este paquete). Necesario porque los componentes de este paquete utilizan estilos de Bootstrap.

    ```bash
    npm install bootstrap@^5.3.5
    # o
    yarn add bootstrap@^5.3.5
    ```

**Librerías de Vue Específicas del Paquete:**

Las siguientes librerías son dependencias directas de este paquete y **deben** instalarse en la aplicación Laravel para su correcto funcionamiento:

```bash
npm install vue-paginate
# o
yarn add vue-paginate
```

### Configuración de Vite para los assets Vue del paquete

Para que los componentes Vue de este paquete funcionen correctamente en tu aplicación Laravel, necesitas asegurarte de que Vite esté configurado para compilar los assets de Vue en tu aplicación de Laravel. Sigue estos pasos:

1.  **Instala Vite y el Plugin de Vue:** Si aún no lo has hecho, instala las dependencias necesarias de Vite en tu proyecto Laravel:

    ```bash
    npm install vite @vitejs/plugin-vue --save-dev
    # o
    yarn add vite @vitejs/plugin-vue --dev
    ```

2.  **Crea o Modifica el Archivo `vite.config.js`:** Si no existe, crea un archivo llamado `vite.config.js` en la raíz de tu proyecto Laravel. Si ya existe, ábrelo y asegúrate de que contenga una configuración similar a la siguiente. **Es crucial que incluyas `@vitejs/plugin-vue` y la ruta al punto de entrada de los scripts de tu paquete en la opción `input` del plugin de Laravel.**

    ```javascript
    import { defineConfig } from "vite";
    import laravel from "laravel-vite-plugin";
    import vue from "@vitejs/plugin-vue"; // <-- ¡Asegúrate de agregar esta línea!

    export default defineConfig({
        plugins: [
            laravel({
                input: [
                    // Otras configuraciones de Vite
                    "resources/vendor/durnerlys/dummy-quotes/js/main.js", // <-- ¡Asegúrate de agregar esta línea!
                ],
                refresh: true,
            }),
            vue(), // <-- ¡Asegúrate de tener esta importación y esta línea en los plugins!
        ],
    });
    ```

    **Explicación Importante:**

    -   **`import vue from '@vitejs/plugin-vue';`**: Esta línea importa el plugin de Vite necesario para procesar tus componentes `.vue`. Asegúrate de que esta importación esté presente en la parte superior del archivo.
    -   **`vue()`**: Asegúrate de que la función `vue()` esté incluida dentro del array `plugins` en la configuración de Vite. Esto habilita el soporte de Vue en el proceso de compilación de Vite.
    -   **`'resources/vendor/durnerlys/dummy-quotes/js/main.js'`**: Esta línea dentro del array `input` del plugin `laravel()` le indica a Vite que también compile el archivo principal de JavaScript del paquete, y registra todos los cambios que quieras efectuar en el diseño UI desarrollado en Vue.

3.  **Ejecuta Vite:** Después de configurar `vite.config.js`, ejecuta los comandos de Vite en tu aplicación Laravel para compilar los assets:

    ```bash
    npm run dev
    # o
    yarn dev
    ```

    Este comando iniciará el servidor de desarrollo de Vite y recompilará los assets de forma automática cuando guardes los cambios. Para la producción, utiliza el comando:

    ```bash
    npm run build
    # o
    yarn build
    ```

Este comando generará los assets optimizados en tu carpeta `public/build` de tu aplicación Laravel.

4.  **Carga los Assets en tus Vistas Blade:** Utiliza la directiva `@vite` en tus archivos Blade de Laravel para incluir los assets compilados de tu paquete:

    ```blade
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Usando Dummy Quotes</title>
        @vite(['resources/vendor/durnerlys/dummy-quotes/js/main.js'])
    </head>
    <body>
        <div id="app">
        </div>
    </body>
    </html>
    ```

    Asegúrate de incluir el punto de entrada de los archivos editables de Vue (`'resources/vendor/durnerlys/dummy-quotes/js/main.js'`) dentro del array pasado a la directiva `@vite`.

## Acceso a la UI

Para acceder a la interfaz de usuario del paquete, dirígete a la siguiente URL en tu navegador:

```

https://app-laravel-example.com/quotes-ui

```

### Modificación y recompilación de las Vistas Vue

Este paquete incluye componentes de interfaz de usuario desarrollados con Vue.js. Si deseas personalizar o modificar estas vistas, sigue estos pasos:

1.  **Localiza los Archivos Vue:** Los archivos fuente de los componentes Vue de este paquete si han sido publicados en tu aplicación Laravel se deben encontrar en el directorio `resources/vendor/durnerlys/dummy-quotes/js/components/`. Aquí tendrás los archivos `.vue` que definen la estructura, la lógica y el estilo de los componentes.

2.  **Modifica los Componentes:** Utiliza tu editor de código preferido para abrir y modificar los archivos `.vue` según tus necesidades.

3.  **Compila los Assets con Vite:** Después de realizar las modificaciones en los archivos Vue, es crucial compilar los assets utilizando Vite para que los cambios se reflejen en tu aplicación Laravel. Asegúrate de estar en la raíz de tu proyecto Laravel (donde se encuentra el archivo `vite.config.js`) y ejecuta el siguiente comando:

    ```bash
    npm run dev
    # o
    yarn dev
    ```

    Este comando iniciará el servidor de desarrollo de Vite y recompilará los assets de forma automática cuando guardes los cambios. Para la producción, utiliza el comando:

    ```bash
    npm run build
    # o
    yarn build
    ```

    Este comando generará los assets optimizados en tu carpeta `public/build` de tu aplicación Laravel.

4.  **Visualiza los Cambios:** Una vez que la compilación con Vite haya finalizado (sin errores), los cambios que realizaste en los componentes Vue deberían reflejarse en tu aplicación Laravel cuando accedas a las rutas o componentes que utilizan las vistas modificadas.

## Configuración

Archivo de configuración flexible para personalizar la URL de la API y la limitación de tasa. A continuación se describen los parámetros disponibles en este archivo.

-   **Parámetros de configuración**

    -   **`base_url`**

        -   **Descripción:** Define la URL base de la API que se utilizará para realizar las solicitudes.
        -   **Valor por defecto:** `'https://dummyjson.com'`
        -   **Ejemplo de uso:**
            ```php
            'base_url' => 'https://dummyjson.com',
            ```

    -   **`max_requests`**

        -   **Descripción:** Establece el número máximo de solicitudes que se pueden realizar dentro de un intervalo de tiempo especificado.
        -   **Valor por defecto:** `60` solicitudes.
        -   **Ejemplo de uso:**
            ```php
            'max_requests' => '60',
            ```

    -   **`time_window`**
        -   **Descripción:** Define la duración del intervalo de tiempo en segundos para la limitación de tasa.
        -   **Valor por defecto:** `60` segundos (un minuto).
        -   **Ejemplo de uso:**
            ```php
            'time_window' => 60,
            ```

## Características

-   **Servicio de Cliente API:** Comunicación con la API de DummyJSON Quotes con limitación de tasa y caché local.
-   **Rutas API:** Endpoints API listos para ser consumidos por la UI de Vue.js:
    -   `/api/quotes`: Devuelve todas las citas.
    -   `/api/quotes/random`: Devuelve una cita aleatoria.
    -   `/api/quotes/{id}`: Devuelve una cita específica por ID.
-   **Interfaz de Usuario Vue.js:** Interfaz intuitiva para explorar las citas.
    -   Ver todas las citas.
    -   Ver una cita aleatoria.
    -   Ver una cita específica por ID.
-   **Assets Publicables:** Los assets compilados de la UI de Vue.js son ser publicados en la aplicación principal.

## Estructura del Servicio de Cliente API

### 1. Interfaces

#### 1.1 `QuoteRetrieverInterface`

Esta interfaz define los métodos que debe implementar cualquier servicio que recupere citas. Los métodos son:

-   `getAllQuotes()`: Recupera todas las citas.
-   `getRandomQuote()`: Recupera una cita aleatoria.
-   `getQuote(int $id)`: Recupera una cita específica por su ID.

#### 1.2 `RateLimiterInterface`

Esta interfaz define los métodos para manejar la limitación de tasa de las solicitudes a la API. Los métodos son:

-   `generateKey(string $identifier)`: Genera una clave única para la limitación de tasa basada en el endpoint y la dirección IP.
-   `callApiWithRateLimit(string $url, array $queryParams = [])`: Llama al endpoint de la API con limitación de tasa aplicada.

#### 1.3 `CacheServiceInterface`

Esta interfaz define los métodos para manejar el almacenamiento en caché. Los métodos son:

-   `get(string $key)`: Recupera un elemento del caché.
-   `put(string $key, mixed $value, int $ttl = 3600)`: Almacena un elemento en el caché.
-   `has(string $key)`: Verifica si un elemento existe en el caché.
-   `getCachedDataWithRateLimit(string $cacheKey, int $id = null)`: Recupera datos del caché, manejando límites de tasa y diferentes claves de caché.
-   `checkRateLimitForCache(string $key)`: Determina si los datos en caché deben ser devueltos según el límite de tasa.
-   `binarySearchLocalCache(string $allQuotesCacheKey, int $id)`: Realiza una búsqueda binaria en el caché local para encontrar una cita por su ID.

### 2. Clases de Servicio

#### 2.1 `DummyQuotesService`

Esta clase es la principal e implementa `QuoteRetrieverInterface` y es responsable de recuperar citas de la API. Sus métodos son:

-   **`getAllQuotes()`**:
    -   Verifica si las citas están en caché. Si están, las devuelve.
    -   Si no están en caché, realiza una solicitud a la API, almacena el resultado en caché y lo devuelve.
-   **`getRandomQuote()`**:
    -   Similar a `getAllQuotes()`, pero recupera una cita aleatoria.
-   **`getQuote(int $id)`**:
    -   Verifica si la cita está en caché. Si está, la devuelve.
    -   Si no está en caché, realiza una solicitud a la API para obtener la cita por ID.

#### 2.2 `CacheService`

Esta clase implementa `CacheServiceInterface` y maneja la lógica de almacenamiento en caché. Sus métodos son:

-   **`get(string $key)`**: Recupera un elemento del caché.
-   **`put(string $key, mixed $value, int $ttl = 3600)`**: Almacena un elemento en el caché.
-   **`checkRateLimitForCache(string $key)`**: Verifica si se puede acceder a los datos en caché según el límite de tasa.
-   **`binarySearchLocalCache(string $allQuotesCacheKey, int $id)`**: Busca una cita por ID en el caché.

#### 2.3 `RateLimiterService`

Esta clase implementa `RateLimiterInterface` y maneja la lógica de limitación de tasa. Sus métodos principales son:

-   **`generateKey(string $identifier)`**: Genera una clave única para la limitación de tasa.
-   **`callApiWithRateLimit(string $url, array $queryParams = [])`**: Realiza una solicitud a la API con limitación de tasa.

## Funcionamiento

1. **Recuperación de Citas**: Cuando se solicita una cita, el servicio primero verifica si la cita está en caché. Si está, la devuelve inmediatamente. Si no, realiza una solicitud a la API, almacena el resultado en caché y lo devuelve.

2. **Limitación de Tasa**: Antes de realizar una solicitud a la API, el servicio verifica si se ha alcanzado el límite de tasa. Si se ha alcanzado, devuelve un mensaje de error indicando que se han realizado demasiadas solicitudes. La verificación del límite de tasa también se ha agregado a las quotes en caché.

3. **Caché**: El servicio utiliza un sistema de caché para almacenar las citas recuperadas, lo que reduce el tiempo de espera.

## Testing

Para ejecutar las pruebas del paquete, utiliza el siguiente comando:

```bash
composer test
```

## Credits

-   [Durnerlys Velásquez](https://github.com/durnerlysdev)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
