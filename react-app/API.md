# Predictorium API Documentation

Base URL: `http://localhost/api`

---

## Endpoints

### GET `/api/ping`

Health check endpoint.

**Response (200):**
```json
{
  "status": true,
  "message": "pong"
}
```

---

### GET `/api/main-menu`

Returns navigation menu items.

**Response (200):**
```json
{
  "data": {
    "items": [
      {
        "label": "Home",
        "url": "home",
        "children": []
      },
      {
        "label": "Simracing",
        "url": "/categories/simracing/tournaments",
        "children": []
      },
      {
        "label": "What to play",
        "url": "what_to_play",
        "children": []
      }
    ]
  },
  "status": true
}
```

---

### GET `/api/home`

Returns the home page content by slug `home`.

**Response (200):**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "slug": "home",
    "is_active": true,
    "params_json": [],
    "created_at": "2026-07-05T13:02:33.000000Z",
    "updated_at": "2026-07-05T13:02:33.000000Z",
    "deleted_at": null,
    "contents": [
      {
        "id": 2,
        "model_type": "App\\Models\\SimplePage",
        "model_id": 1,
        "lang": "en",
        "title": "What is it",
        "content": "<p>It is a Home page with description</p>",
        "created_at": "2026-07-07T13:33:08.000000Z",
        "updated_at": "2026-07-07T13:33:08.000000Z"
      }
    ]
  }
}
```

---

### GET `/api/what_to_play`

Returns the "What to Play" page content by slug `what_to_play`.

**Response (200):**
```json
{
  "status": true,
  "data": {
    "id": 2,
    "slug": "what_to_play",
    "is_active": true,
    "params_json": [],
    "created_at": "2026-07-05T13:26:19.000000Z",
    "updated_at": "2026-07-05T13:26:19.000000Z",
    "deleted_at": null,
    "contents": [
      {
        "id": 3,
        "model_type": "App\\Models\\SimplePage",
        "model_id": 2,
        "lang": "en",
        "title": "What to play",
        "content": "<p>This page will show list of some games with current parice and current Online</p>",
        "created_at": "2026-07-07T13:34:01.000000Z",
        "updated_at": "2026-07-07T13:34:01.000000Z"
      }
    ]
  }
}
```

---

### GET `/api/categories/{slug}`

Returns a category with its contents and active tournaments.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| slug | string | Category slug (e.g., `simracing`) |

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Simracing",
    "slug": "simracing",
    "is_active": true,
    "sort_order": 1,
    "icon": "heroicon-o-football",
    "contents": [
      {
        "id": 1,
        "title": "Simracing tournaments",
        "content": "<hr><hr><hr><hr><p></p>",
        "lang": "en"
      }
    ],
    "tournaments": [
      {
        "id": 1,
        "name": "Test Tournament",
        "slug": "test_tournament",
        "description": "Racing tournament. Test all markets",
        "status": "active",
        "start_date": "2026-07-01 20:42",
        "end_date": "2026-07-31 20:42"
      },
      {
        "id": 2,
        "name": "New Tour. Ahtung race",
        "slug": "new_tour",
        "description": null,
        "status": "active",
        "start_date": "2026-07-20 12:48",
        "end_date": null
      }
    ]
  },
  "status": true
}
```

**Response (404):**
```json
{
  "status": false,
  "message": "Category not found"
}
```

---

### GET `/api/tournaments/{tournamentId}`

Returns a tournament with its contents, category, and active events.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| tournamentId | integer | Tournament ID |

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Test Tournament",
    "slug": "test_tournament",
    "description": "Racing tournament. Test all markets",
    "status": "active",
    "start_date": "2026-07-01 20:42",
    "end_date": "2026-07-31 20:42",
    "category": {
      "id": 1,
      "name": "Simracing",
      "slug": "simracing"
    },
    "contents": [],
    "events": [
      {
        "id": 1,
        "name": "Race Stage 1",
        "slug": "race_stage_1",
        "status": "active",
        "start_date": "2026-07-02 21:28",
        "end_date": "2026-07-02 21:50"
      },
      {
        "id": 2,
        "name": "Race Stage 2",
        "slug": "race_stage_2",
        "status": "active",
        "start_date": "2026-07-03 21:30",
        "end_date": "2026-07-03 22:30"
      }
    ]
  },
  "status": true
}
```

**Response (404):**
```json
{
  "status": false,
  "message": "Tournament not found"
}
```

---

### GET `/api/events/{eventId}`

Returns an event with its contents, tournament, and markets with outcomes.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| eventId | integer | Event ID |

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Race Stage 1",
    "slug": "race_stage_1",
    "status": "active",
    "start_date": "2026-07-02 21:28",
    "end_date": "2026-07-02 21:50",
    "tournament": {
      "id": 1,
      "name": "Test Tournament",
      "slug": "test_tournament"
    },
    "contents": [],
    "markets": [
      {
        "id": 9,
        "description": "Participants list",
        "outcomes": [
          {
            "id": 16,
            "coef": "1.20",
            "result": null,
            "outcome_type": {
              "id": 3,
              "name": "participant"
            },
            "participant": {
              "id": 1,
              "name": "Pilot 1"
            }
          },
          {
            "id": 17,
            "coef": "1.00",
            "result": null,
            "outcome_type": {
              "id": 3,
              "name": "participant"
            },
            "participant": {
              "id": 2,
              "name": "Pilot 2"
            }
          }
        ]
      },
      {
        "id": 13,
        "description": "Boolean ( Yes / No )",
        "outcomes": [
          {
            "id": 52,
            "coef": "1.00",
            "result": null,
            "outcome_type": {
              "id": 1,
              "name": "yes"
            },
            "participant": null
          },
          {
            "id": 53,
            "coef": "1.00",
            "result": null,
            "outcome_type": {
              "id": 2,
              "name": "no"
            },
            "participant": null
          }
        ]
      }
    ]
  },
  "status": true
}
```

**Response (404):**
```json
{
  "status": false,
  "message": "Event not found"
}
```

---

## Auth Endpoints

### POST `/api/auth/register`

Register a new user.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

---

### POST `/api/auth/login`

Authenticate a user.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password"
}
```

---

### POST `/api/auth/logout`

Logout the authenticated user. Requires `Authorization: Bearer {token}` header.

---

### GET `/api/auth/me`

Get the authenticated user. Requires `Authorization: Bearer {token}` header.

---

## Error Responses

All error responses follow this format:

```json
{
  "message": "Error description",
  "exception": "ExceptionClassName",
  "file": "/path/to/file.php",
  "line": 123
}
```
