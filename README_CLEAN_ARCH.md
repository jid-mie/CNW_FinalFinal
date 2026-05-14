# Laravel Clean Architecture Base

## Layers

```txt
app/
├── Domain/                 # Enterprise/business rules
│   └── User/
│       ├── Entities/
│       └── Repositories/   # Interfaces/contracts
├── Application/            # Use cases/application rules
│   └── User/
│       ├── DTOs/
│       └── UseCases/
├── Infrastructure/         # Framework/db/external impl
│   └── Persistence/
│       └── Eloquent/
│           └── Repositories/
└── Http/                   # Delivery layer
    ├── Controllers/
    └── Requests/
```

## Dependency rule

`Http → Application → Domain ← Infrastructure`

Domain has no Laravel dependency.
Application depends on Domain contracts.
Infrastructure implements Domain contracts.
Http only calls UseCases.

## Example endpoint

```http
POST /api/users
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123"
}
```

## Run

```bash
cd laravel-clean-arch
php artisan serve
```
