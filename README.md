# zero
"The circle closes only to open again; what ends is already beginning, and what begins has always been ending."

# Laravel Zero Package - Carrot Management

This package provides a complete repository pattern implementation for managing Carrots and their polymorphic relationships.

## Installation

1. Add the service provider to your `config/app.php`:
```php
'providers' => [
    // ...
    Hetbo\Zero\ZeroServiceProvider::class,
];
```

2. Run migrations:
```bash
php artisan migrate
```

3. Publish config (optional):
```bash
php artisan vendor:publish --provider="Hetbo\Zero\ZeroServiceProvider" --tag="config"
```

## Usage

### Using the HasCarrots trait

Add the trait to any model that should have carrots:

```php
use Hetbo\Zero\Traits\HasCarrots;

class User extends Model
{
    use HasCarrots;
    
    // Your model code...
}
```

### Basic Operations

```php
// Create a carrot
$carrot = app(CarrotService::class)->createCarrot(
    CreateCarrotData::fromArray(['name' => 'Big Carrot', 'length' => 25])
);

// Attach carrot to a model with role
$user = User::find(1);
app(CarrotableService::class)->attachCarrotToModel($user, $carrot->id, 'favorite');

// Get user's carrots by role
$favoriteCarrots = app(CarrotableService::class)->getModelCarrotsByRole($user, 'favorite');

// Sync carrots for a specific role
app(CarrotableService::class)->syncCarrotsForModel($user, [1, 2, 3], 'favorite');
```

### API Endpoints

#### Carrot Management
- GET `/api/carrots` - List all carrots (with pagination)
- POST `/api/carrots` - Create a new carrot
- GET `/api/carrots/{id}` - Show specific carrot
- PUT `/api/carrots/{id}` - Update carrot
- DELETE `/api/carrots/{id}` - Delete carrot
- GET `/api/carrots/search/name?name=search` - Search by name
- GET `/api/carrots/search/length?min_length=1&max_length=10` - Search by length range

#### Relationship Management
- POST `/api/carrotables/attach` - Attach carrot to model
- POST `/api/carrotables/detach` - Detach carrot from model
- POST `/api/carrotables/sync` - Sync carrots for specific role
- GET `/api/carrotables/carrots?model_type=App\User&model_id=1&role=favorite` - Get carrots by role
- GET `/api/carrotables/carrots/all?model_type=App\User&model_id=1` - Get all carrots for model
- GET `/api/carrotables/roles?model_type=App\User&model_id=1` - Get all roles for model

### Request Examples

#### Create Carrot
```bash
curl -X POST /api/carrots \
  -H "Content-Type: application/json" \
  -d '{"name": "Orange Carrot", "length": 15}'
```

#### Attach Carrot to Model
```bash
curl -X POST /api/carrotables/attach \
  -H "Content-Type: application/json" \
  -d '{
    "model_type": "App\\User",
    "model_id": 1,
    "carrot_id": 1,
    "role": "favorite"
  }'
```

#### Sync Carrots
```bash
curl -X POST /api/carrotables/sync \
  -H "Content-Type: application/json" \
  -d '{
    "model_type": "App\\User", 
    "model_id": 1,
    "carrot_ids": [1, 2, 3],
    "role": "favorite"
  }'
```

## Testing

Run the tests with:
```bash
php artisan test
```

## Architecture

The package follows these patterns:

- **Repository Pattern**: Clean separation between data access and business logic
- **Service Layer**: Business logic and coordination between repositories
- **DTO Pattern**: Type-safe data transfer objects
- **Request Validation**: Laravel Form Requests for input validation
- **Dependency Injection**: All dependencies injected through constructor

## Configuration

The package includes configuration for pagination, caching, and validation rules. Publish the config file to customize these settings.
