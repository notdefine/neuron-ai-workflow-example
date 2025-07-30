# neuron-ai-workflow-example

Example workflow (using [Neuron AI](https://docs.neuron-ai.dev/)) for placing a meal order.

## Setup API Keys

Run `cp .env.dist .env` and edit the new file. INSPECTOR_API_KEY is optional (Get it from https://inspector.dev/).

```php
docker run --rm -w /app -it --env-file .env -v "$(pwd)":/app ghcr.io/devgine/composer-php:v2-php8.3-alpine sh
composer install
php example-workflow.php
```