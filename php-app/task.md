name: Laravel API Developer
description: Develops clean, testable Laravel API endpoints using Layered Architecture (Controller-Service-Repository).
triggers: [laravel api, create endpoint, build api, generate route, service layer, repository pattern]

Purpose
To design and implement robust, scalable, and maintainable RESTful API endpoints in Laravel, ensuring separation of concerns and comprehensive test coverage.

When to use
Creating new API features or endpoints.

Refactoring existing controllers into a layered architecture.

Writing feature tests for existing or new business logic.

Standardizing database interactions and business rules.

Inputs needed
Entity/Feature description (what the endpoint should do).

Required HTTP method (GET, POST, PUT, DELETE).

Validation rules (fields and constraints).

Business logic requirements (what happens to the data).

Procedure
Define Enums: Create backed Enums for any status, role, or category constants.

Define FormRequest: Implement validation logic in a FormRequest class.

Create Repository: Encapsulate all Eloquent queries in a repository class.

Implement Service: Place all business logic in a service class, injecting the repository via the constructor.

Build Controller: Keep the controller thin; delegate logic to the service and return an API Resource.

Define Routes: Add the endpoint to api.php with a unique name.

Write Feature Test: Create a test file that exercises the named route and asserts success/failure responses.

Output format
Files generated: Path to each file created.

Code: Syntax-highlighted code blocks for each layer.

Test: The corresponding Feature test class.

Quality bar (self-check)
[ ] Is the code free of hardcoded strings (Enums used)?

[ ] Is the business logic entirely outside the Controller?

[ ] Does the Controller use dependency injection for the Service?

[ ] Is every route assigned a ->name()?

[ ] Does the Feature Test use the named route?

[ ] Does the response utilize an API Resource for data transformation?

Anti-patterns
❌ Putting database queries directly in the Controller.

❌ Using $request->validate() inside the Controller instead of FormRequest.

❌ Returning raw Eloquent models instead of JSON Resources.

❌ Hardcoding status strings or roles without an Enum.

❌ Writing "Integration" tests that skip validation or authorization checks.

Examples
Input: "Create a POST endpoint to register a user, assign them a 'member' role, and return a 201 response."
Output:

Enum: UserRole.php

FormRequest: RegisterRequest.php

Repository: UserRepository.php

Service: UserService.php

Controller: RegisterController.php

Test: RegisterApiTest.php (asserting route('api.auth.register'))

Дополнительные рекомендации для разработчика API на Laravel:
Чтобы ваш AI-агент стал еще эффективнее, рекомендую добавить в его «арсенал» следующие методологии:

Strict Data Transfer (DTOs): Требуйте использования DTO для передачи данных между контроллером и сервисом. Это гарантирует, что сервис не зависит от структуры объекта Request.

API Versioning: Обяжите агента всегда помещать эндпоинты в неймспейсы версий (например, Route::prefix('v1')), чтобы избежать проблем с обратной совместимостью в будущем.

Standardized JSON Structure: Попросите агента всегда использовать единый формат ответа, например:

JSON
{
"data": { ... },
"meta": { ... },
"message": "Success message"
}
Database Transactions: Если сервис выполняет запись в несколько таблиц, обязательно используйте DB::transaction() для обеспечения целостности данных.

Logging & Monitoring: Интегрируйте логирование критических операций через Log::channel('daily'), чтобы агент автоматически добавлял логи в методы сервисов, где происходят важные изменения состояния.
