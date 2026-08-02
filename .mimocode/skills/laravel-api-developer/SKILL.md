---
name: laravel-api-developer
description: Develops clean, testable Laravel API endpoints using Layered Architecture (Controller-Service-Repository).
triggers: [laravel api, create endpoint, build api, generate route, service layer, repository pattern]
---

# Laravel API Developer

## Purpose

To design and implement robust, scalable, and maintainable RESTful API endpoints in Laravel, ensuring separation of concerns and comprehensive test coverage.

## When to Use

- Creating new API features or endpoints.
- Refactoring existing controllers into a layered architecture.
- Writing feature tests for existing or new business logic.
- Standardizing database interactions and business rules.

## Inputs Needed

- Entity/Feature description (what the endpoint should do).
- Required HTTP method (GET, POST, PUT, DELETE).
- Validation rules (fields and constraints).
- Business logic requirements (what happens to the data).

## Procedure

1. **Define Enums**: Create backed Enums for any status, role, or category constants.

2. **Define FormRequest**: Implement validation logic in a FormRequest class.

3. **Create Repository**: Encapsulate all Eloquent queries in a repository class.

4. **Implement Service**: Place all business logic in a service class, injecting the repository via the constructor.

5. **Build Controller**: Keep the controller thin; delegate logic to the service and return an API Resource.

6. **Define Routes**: Add the endpoint to api.php with a unique name.

7. **Write Feature Test**: Create a test file that exercises the named route and asserts success/failure responses.

## Output Format

- **Files generated**: Path to each file created.
- **Code**: Syntax-highlighted code blocks for each layer.
- **Test**: The corresponding Feature test class.

## Quality Bar (Self-Check)

- [ ] Is the code free of hardcoded strings (Enums used)?
- [ ] Is the business logic entirely outside the Controller?
- [ ] Does the Controller use dependency injection for the Service?
- [ ] Is every route assigned a ->name()?
- [ ] Does the Feature Test use the named route?
- [ ] Does the response utilize an API Resource for data transformation?

## Anti-patterns

- Putting database queries directly in the Controller.
- Using $request->validate() inside the Controller instead of FormRequest.
- Returning raw Eloquent models instead of JSON Resources.
- Hardcoding status strings or roles without an Enum.
- Writing "Integration" tests that skip validation or authorization checks.

## Additional Recommendations

### Strict Data Transfer (DTOs)
Require using DTOs for data transfer between controller and service. This ensures the service doesn't depend on the Request object structure.

### API Versioning
Always place endpoints in versioned namespaces (e.g., `Route::prefix('v1')`) to avoid backward compatibility issues.

### Standardized JSON Structure
Always use a unified response format:
```json
{
  "data": { ... },
  "meta": { ... },
  "message": "Success message"
}
```

### Database Transactions
When the service writes to multiple tables, use `DB::transaction()` to ensure data integrity.

### Logging & Monitoring
Integrate logging for critical operations via `Log::channel('daily')`, adding logs in service methods where important state changes occur.

## Example

**Input**: "Create a POST endpoint to register a user, assign them a 'member' role, and return a 201 response."

**Output**:
- Enum: `UserRole.php`
- FormRequest: `RegisterRequest.php`
- Repository: `UserRepository.php`
- Service: `UserService.php`
- Controller: `RegisterController.php`
- Test: `RegisterApiTest.php` (asserting `route('api.auth.register')`)

---

## Predictorium Project Conventions (Extension)

### Folder Structure

All Trading domain code lives under `Trading/` subfolders:
```
app/Http/Controllers/Trading/
app/Services/Trading/
app/Repositories/Trading/
app/Http/Requests/Trading/
app/Http/Resources/Trading/
```

### Routing

- All API routes in `routes/api.php`
- Every route **must** have `->name('api.<entity>.<action>')`
- Slug-based routing: endpoints accept slug, resolve to ID internally
- Protected routes inside `auth:sanctum` middleware group

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/stake', [StakeController::class, 'store'])->name('api.stakes.store');
    Route::get('/stakes/stat/{marketId}', [StakeController::class, 'stat'])->name('api.stakes.stat');
});
```

### Date Format

All date outputs: `yyyy-mm-dd HH:ii` (e.g., `2026-03-01 14:30`)
```php
// In API Resource
'start_date' => $this->start_date->format('Y-m-d H:i'),
```

### Response Format

Standardized JSON structure:
```json
{
  "status": true,
  "data": { ... },
  "message": "Success message"
}
```
- `status: true` for success, `false` for error
- `data` contains the resource/collection
- `message` optional human-readable message

### JsonResource Returns 201

When a `JsonResource` is returned from a controller creating a new record, Laravel returns **HTTP 201**.
- Tests **must** use `assertCreated()` for POST create endpoints
- Not `assertOk()`

### Validation

- FormRequest classes in `app/Http/Requests/Trading/`
- Event status validation: check `event->status === EventStatus::Active`
- Unique constraint checks at application level (no DB unique composite indexes for some entities)

### Enums

Use PHP 8.1+ backed enums with `label()` and `color()` methods for Filament:
```php
enum EventStatus: string {
    case Active = 'active';
    case Finished = 'finished';
    case Cancelled = 'cancelled';
    
    public function label(): string { ... }
    public function color(): string { ... }
}
```

Model casts:
```php
protected function casts(): array {
    return ['status' => EventStatus::class];
}
```

### Database Transactions

Use `DB::transaction()` in Service when writing to multiple tables (e.g., creating stake + updating related entities).

### Testing

- Extend `TestCase`, use `RefreshDatabase`
- No factories for Event, Market, Outcome, Tournament, TournamentConfig
- Use `DB::table()->insertGetId()` for deep hierarchy setup
- Hardcode seeder IDs for market_type, outcome_type, result_type
- See `laravel-deep-test-setup` skill for full test setup pattern

### Auth

- Sanctum token auth: `auth:sanctum` middleware
- Controller methods receive authenticated user via `$request->user()`
- Customer role for frontend users (distinct from admin/editor/user)

### Spatie RBAC

- Roles: `admin`, `editor`, `user` (seeded in `RolesAndPermissionsSeeder`)
- **Seeder order matters**: `RolesAndPermissionsSeeder` MUST run before any user seeder
- Permissions checked via policies or middleware

### Filament Integration

- Admin panel at `/admin` auto-discovers Resources in `app/Filament/Resources/`
- Resources use slug-based routing via `getRecordRouteBinding()`
