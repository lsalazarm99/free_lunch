# The warehouse

This service takes care of the available amount of ingredients, the orders that request them and their purchases.

## Endpoints

- `GET /ingredient/all` - Gets all the ingredients.
- `GET /ingredient_purchase/search` - Get a paginated list of ingredient purchases filtered according to the parameters 
  provided. The following parameters are available:
    - `ingredient_id` - An optional integer value. It indicates that the ingredient purchase **should have** the 
      indicated ingredient.
    - `date_from` - An optional string value. It should be a valid string date. It indicates that the ingredient 
      purchase **should have been** created after the indicated date.
    - `date_to` - An optional string value. It should be a valid string date. It indicates that the ingredient
      purchase **should have been** created before the indicated date.
    - `max_items_number` - An optional integer value. It indicates the number of items of each page of the paginated
      response. The minimum value is `1` and the maximum value is `15`.
- `POST /order` - Creates an order. The following parameters are available:
    - `order_id` - An integer value.
    - `ingredients` - An array value. It should have at least one item.
    - `ingredients.*.id` - An integer value. It should be a valid ingredient ID.
    - `ingredients.*.amount` - An integer value. It indicates the required amount of the ingredient for the order.

## Commands

- `warehouse:buy-ingredients` - Buy required ingredients from the food shop.
- `warehouse:process-orders` - Process the undelivered orders and deliver them if possible.

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

The web server will be listening in the port 8001.

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

## Applying Coding Standards

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
