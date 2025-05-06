<p align="center">
    <a href="./LEAME.md">Español / Spanish</a>
</p>

# Dummy Quotes

This Laravel package provides a user interface built with Vue.js to interact with the [DummyJSON Quotes](https://dummyjson.com/quotes) API. It offers functionalities such as retrieving all quotes, random quotes, and specific quotes by ID, while also implementing request rate limiting and efficient local caching with binary search.

## Installation

You can install this package via Composer:

```bash
composer require durnerlys/dummy-quotes
```

## Publishing Assets

To publish the provider's assets, you can use the following Artisan commands. It is recommended to publish all assets to ensure everything is available in your application.

**Publish all provider assets (recommended):**

```bash
php artisan vendor:publish --provider="Durnerlys\DummyQuotes\DummyQuotesServiceProvider"
```

**Or, if you prefer to publish by specific tags:**

```bash
# API Routes
php artisan vendor:publish --tag="dummy-quotes-routes"

# Blade View (Entry to Vue views)
php artisan vendor:publish --tag="dummy-quotes-views"

# Vue.js Sources (folder 'resources/js')
php artisan vendor:publish --tag="dummy-quotes-vue-sources"

# Compiled Assets (Vue 'dist' folder)
php artisan vendor:publish --tag="dummy-quotes-assets"
```

## Dependencies

This package utilizes Vue.js for its user interface components. To ensure proper functionality, the Laravel application consuming this package needs to have the following dependencies installed:

**Vue Dependencies:**

-   `vue`: (Minimum recommended version: `^3.5.13` or the version used by this package). Necessary to render the Vue components provided by this package.

    ```bash
    npm install vue@^3.5.13
    # or
    yarn add vue@^3.5.13
    ```

-   `vue-router`: (Minimum recommended version: `^4.5.1` or the version used by this package). Required because this package uses Vue Router within its components.

        ```bash
        npm install vue-router@^4.5.1
        # or
        yarn add vue-router@^4.5.1
        ```

    **Other CSS/UI Dependencies:**

-   `bootstrap`: (Minimum recommended version: `^5.3.5` or the version used by this package). Necessary because the components in this package utilize Bootstrap styles.

    ```bash
    npm install bootstrap@^5.3.5
    # or
    yarn add bootstrap@^5.3.5
    ```

**Package-Specific Vue Libraries:**

The following libraries are direct dependencies of this package and **must** be installed in the Laravel application for proper functionality:

```bash
npm install vue-paginate
# or
yarn add vue-paginate
```

### Vite Configuration for Package Vue Assets

For the Vue components in this package to function correctly within your Laravel application, you need to ensure that Vite is configured to compile the package's Vue assets within your Laravel application. Follow these steps:

1.  **Install Vite and the Vue Plugin:** If you haven't already, install the necessary Vite dependencies in your Laravel project:

    ```bash
    npm install vite @vitejs/plugin-vue --save-dev
    # or
    yarn add vite @vitejs/plugin-vue --dev
    ```

2.  **Create or Modify the `vite.config.js` File:** If it doesn't exist, create a file named `vite.config.js` in the root of your Laravel project. If it already exists, open it and ensure it contains a configuration similar to the following. **It is crucial that you include `@vitejs/plugin-vue` and the path to your package's scripts entry point in the `input` option of the Laravel plugin.**

    ```javascript
    import { defineConfig } from "vite";
    import laravel from "laravel-vite-plugin";
    import vue from "@vitejs/plugin-vue"; // <-- Make sure to add this line!

    export default defineConfig({
        plugins: [
            laravel({
                input: [
                    // Other Vite configurations
                    "resources/vendor/durnerlys/dummy-quotes/js/main.js", // <-- Make sure to add this line!
                ],
                refresh: true,
            }),
            vue(), // <-- Make sure you have this import and this line in the plugins!
        ],
    });
    ```

    **Important Explanation:**

    -   **`import vue from '@vitejs/plugin-vue';`**: This line imports the Vite plugin necessary to process your `.vue` components. Ensure this import is present at the top of the file.
    -   **`vue()`**: Ensure that the `vue()` function is included within the `plugins` array in the Vite configuration. This enables Vue support in the Vite compilation process.
    -   **`'resources/vendor/durnerlys/dummy-quotes/js/main.js'`**: This line within the `input` array of the `laravel()` plugin tells Vite to also compile the main JavaScript file of your package, and registers all the changes you want to make to the UI design developed in Vue.

3.  **Run Vite:** After configuring `vite.config.js`, execute the Vite commands in your Laravel application to compile the assets:

    ```bash
    npm run dev
    # or
    yarn dev
    ```

    This command will start the Vite development server and automatically recompile the assets when you save changes. For production, use the command:

    ```bash
    npm run build
    # or
    yarn build
    ```

    This command will generate the optimized assets in your Laravel application's `public/build` directory.

4.  **Load Assets in your Blade Views:** Use the `@vite` directive in your Laravel Blade files to include the compiled assets of your package:

    ```blade
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Using Dummy Quotes</title>
        @vite(['resources/vendor/durnerlys/dummy-quotes/js/main.js'])
    </head>
    <body>
        <div id="app">
        </div>
    </body>
    </html>
    ```

    Make sure to include the entry point of your editable Vue files (`'resources/vendor/durnerlys/dummy-quotes/js/main.js'`) within the array passed to the `@vite` directive.

## Accessing the UI

To access the package's user interface, go to the following URL in your browser:

```bash
https://app-laravel-example.com/quotes-ui
```

### Modifying and Recompiling Vue Views

This package includes user interface components developed with Vue.js. If you wish to customize or modify these views, follow these steps:

1.  **Locate the Vue Files:** The source files for the Vue components of this package, if they have been published to your Laravel application, should be located in the `resources/vendor/durnerlys/dummy-quotes/js/components/` directory. Here you will find the `.vue` files that define the structure, logic, and styling of the components.

2.  **Modify the Components:** Use your preferred code editor to open and modify the `.vue` files according to your needs.

3.  **Compile Assets with Vite:** After making modifications to the Vue files, it is crucial to compile the assets using Vite for the changes to be reflected in your Laravel application. Ensure you are in the root directory of your Laravel project (where the `vite.config.js` file is located) and run the following command:

    ```bash
    npm run dev
    # or
    yarn dev
    ```

    This command will start the Vite development server and automatically recompile the assets when you save changes. For production, use the command:

    ```bash
    npm run build
    # or
    yarn build
    ```

    This command will generate the optimized assets in your Laravel application's `public/build` directory.

4.  **Visualize the Changes:** Once the Vite compilation is complete (without errors), the changes you made to the Vue components should be reflected in your Laravel application when you access the routes or components that utilize the modified views.

## Configuration

Flexible configuration file to customize the API URL and rate limiting. Below are the available parameters in this file.

-   **Configuration Parameters**

    -   **`base_url`**

        -   **Description:** Defines the base URL of the API that will be used to make requests.
        -   **Default value:** `'https://dummyjson.com'`
        -   **Usage example:**
            ```php
            'base_url' => 'https://dummyjson.com',
            ```

    -   **`max_requests`**

        -   **Description:** Sets the maximum number of requests that can be made within a specified time interval.
        -   **Default value:** `60` requests.
        -   **Usage example:**
            ```php
            'max_requests' => '60',
            ```

    -   **`time_window`**
        -   **Description:** Defines the duration of the time interval in seconds for rate limiting.
        -   **Default value:** `60` seconds (one minute).
        -   **Usage example:**
            ```php
            'time_window' => 60,
            ```

## Features

-   **API Client Service:** Communication with the DummyJSON Quotes API with rate limiting and local caching.
-   **API Routes:** API endpoints ready to be consumed by the Vue.js UI:
    -   `/api/quotes`: Returns all quotes.
    -   `/api/quotes/random`: Returns a random quote.
    -   `/api/quotes/{id}`: Returns a specific quote by ID.
-   **Vue.js User Interface:** Intuitive interface to explore quotes.
    -   View all quotes.
    -   View a random quote.
    -   View a specific quote by ID.
-   **Publishable Assets:** The compiled assets of the Vue.js UI are to be published in the main application.

## Customer Service API Structure

### 1. Interfaces

This interface defines the methods that any service retrieving quotes must implement. The methods are:

-   `getAllQuotes()`: Retrieves all quotes.
-   `getRandomQuote()`: Retrieves a random quote.
-   `getQuote(int $id)`: Retrieves a specific quote by its ID.

This interface defines the methods for handling rate limiting of API requests. The methods are:

-   `generateKey(string $identifier)`: Generates a unique key for rate limiting based on the endpoint and IP address.
-   `callApiWithRateLimit(string $url, array $queryParams = [])`: Calls the API endpoint with rate limiting applied.

This interface defines the methods for handling caching. The methods are:

-   `get(string $key)`: Retrieves an item from the cache.
-   `put(string $key, mixed $value, int $ttl = 3600)`: Stores an item in the cache.
-   `has(string $key)`: Checks if an item exists in the cache.
-   `getCachedDataWithRateLimit(string $cacheKey, int $id = null)`: Retrieves data from the cache, handling rate limits and different cache keys.
-   `checkRateLimitForCache(string $key)`: Determines if cached data should be returned based on the rate limit.
-   `binarySearchLocalCache(string $allQuotesCacheKey, int $id)`: Performs a binary search in the local cache to find a quote by its ID.

### 2. Service Classes

This class is the main one and implements `QuoteRetrieverInterface`, responsible for retrieving quotes from the API. Its methods are:

-   **`getAllQuotes()`**:
    -   Checks if the quotes are cached. If they are, it returns them.
    -   If they are not cached, it makes a request to the API, stores the result in the cache, and returns it.
-   **`getRandomQuote()`**:
    -   Similar to `getAllQuotes()`, but retrieves a random quote.
-   **`getQuote(int $id)`**:
    -   Checks if the quote is cached. If it is, it returns it.
    -   If it is not cached, it makes a request to the API to get the quote by ID.

This class implements `CacheServiceInterface` and handles the caching logic. Its methods are:

-   **get(string $key)**: Retrieves an item from the cache.
-   **put(string $key, mixed $value, int $ttl = 3600)**: Stores an item in the cache.
-   **checkRateLimitForCache(string $key)**: Checks if cached data can be accessed based on the rate limit.
-   **binarySearchLocalCache(string $allQuotesCacheKey, int $id)**: Searches for a quote by ID in the cache.

This class implements `RateLimiterInterface` and handles the rate limiting logic. Its main methods are:

-   **generateKey(string $identifier)**: Generates a unique key for rate limiting.
-   **callApiWithRateLimit(string $url, array $queryParams = \[\])**: Makes a request to the API with rate limiting.

## Operation

1. **Quote Retrieval**: When a quote is requested, the service first checks if the quote is cached. If it is, it returns it immediately. If not, it makes a request to the API, stores the result in the cache, and returns it.
2. **Rate Limiting**: Before making a request to the API, the service checks if the rate limit has been reached. If it has, it returns an error message indicating that too many requests have been made. Rate limit checking has also been added to cached quotes.
3. **Caching**: The service uses a caching system to store retrieved quotes, reducing wait time.

## Testing

To run the package tests, use the following command:

```bash
composer test
```

## Credits

-   [Durnerlys Velásquez](https://github.com/durnerlysdev)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
