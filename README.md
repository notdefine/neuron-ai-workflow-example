# neuron-ai-workflow-example

Example workflow (using [Neuron AI](https://docs.neuron-ai.dev/)) for placing a meal order.

```php
docker run --rm -w /app -it --env-file .env -v "$(pwd)":/app ghcr.io/devgine/composer-php:v2-php8.3-alpine sh
composer install
php example-workflow.php
```