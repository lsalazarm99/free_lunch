# The kitchen

This service takes care of the available recipes and its ingredients, the orders that have been requested and their
status.

## Endpoints

- `GET /recipe/{id}` - Gets the recipe according to the indicated ID.
- `GET /recipe/all` - Gets all the recipes.
- `GET /order/{id}` - Gets the order according to the indicated ID.
- `GET /order/search` - Get a paginated list of orders filtered according to the parameters provided. The following
  parameters are available:
    - `in_process` - An optional boolean value. It indicates that the order **could or could not be** in process.
    - `completed` - An optional boolean value. It indicates that the order **could or could not be** completed.
    - `cancelled` - An optional boolean value. It indicates that the order **could or could not be** cancelled.
    - `recipe_id` - An optional integer value. It indicates that the order **should have** the indicated recipe.
    - `max_items_number` - An optional integer value. It indicates the number of items of each page of the paginated
      response. The minimum value is `1` and the maximum value is `15`.
- `POST /order/random` - Creates an order with a random recipe.
- `PUT /order/deliverIngredients/{id}` - Indicates that the required ingredients for the order with the indicated ID are
  delivered.

## Development

You can use Docker and Docker Compose in order to start the service, and also a database and a web server for it. To do
that, run the following command:

```bash
docker-compose -f docker-compose.yaml -f docker-compose.development.yaml up -d
```

In order to install the dependencies, you can run the following command:

```bash
docker-compose exec app composer install
```

The web server will be listening in the port 80.

## Debugging

If you used the `docker-compose.development.yaml` file, you will already have XDebug installed in you image. In order to
configure it, you can use environment variables. Those variables could be placed in
a `docker-compose.development.override.yaml` file, for example. Its content should be similar to this:

```yaml
services:
  app:
    environment:
      # Xdebug configuration.
      # See https://xdebug.org/docs/all_settings.
      XDEBUG_CONFIG: "client_host=host.docker.internal"
      # Set this variable to "debug" in order to enable Xdebug debugging.
      XDEBUG_MODE: "off"
      XDEBUG_SESSION: "1"
```

To instruct Docker Compose to use this file, you can run the following command:

```bash
docker-compose -f docker-compose.yaml -f docker-compose.development.yaml -f docker-compose.development.override.yaml up -d
```

## Testing

Run the following command in order to run the tests:

```bash
docker-compose exec app php artisan test
```

### Applying Coding Standards

Run the following command in order to run PHP CS Fixer in dry mode, so you can check for parts of the code that are not
following the coding standards of the project:

```bash
docker-compose exec app composer run-script php-cs-fixer:dry
```

If you want the tool to automatically fix them, you can use the following command:

```bash
docker-compose exec app composer run-script php-cs-fixer:fix
```

You can also check for PHPStan errors by running the following command:

```bash
docker-compose exec app composer run-script phpstan
```
