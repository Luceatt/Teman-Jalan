# Project Name: Teman-Jalan
**Type**: Web Application (Travel Companion)

## 1. Overview
"Teman-Jalan" is a web-based application designed to help users plan trips, manage itineraries (rundowns), discover places, and connect with friends for shared travel experiences. It combines social features with practical trip planning tools.

## 2. Technology Stack
*   **Backend Framework**: Laravel 12.0 (PHP 8.2+)
*   **Frontend Build Tool**: Vite
*   **Styling**: TailwindCSS (v4)
*   **Database**: SQLite (Development), likely compatible with MySQL/PostgreSQL in production.
*   **Templating**: Blade Templates (implied by `resources/views` structure)

## 3. Core Features & Modules

### 3.1 Authentication
*   **Guest Access**: Landing page, Login, Register.
*   **User Access**: Dashboard, Profile, Logout.
*   **Middleware**: Standard `auth` and `guest` guards.

### 3.2 Trip Planning (Rundowns)
The core feature of the application is the "Rundown" (Itinerary).
*   **Entity**: `Rundown` (Table: `rundowns`)
*   **Functionality**:
    *   Create, Edit, Delete Rundowns.
    *   **Status Workflow**: Draft -> Published -> Completed.
    *   **Activities**: Add activities to a rundown, linked to specific Places.
    *   **Timeline**: View activities in a chronological order.
    *   **Map Data**: Visualize rundown locations on a map.
    *   **Public/Private**: Toggle visibility of rundowns.
    *   **Export/Publish**: Share rundowns with others.

### 3.3 Place Management
*   **Entity**: `Place` (Table: `places`)
*   **Functionality**:
    *   CRUD operations for Places.
    *   **Geospatial**: Stores Latitude/Longitude.
    *   **Search**: Find places by name/category.
    *   **Nearby**: Find places near a location (implied endpoint).
    *   **Categories**: Categorize places (e.g., Restaurant, Park).

### 3.4 Social & Friends
*   **Entity**: `User`, `Friendship` (Table: `friendships`)
*   **Functionality**:
    *   Manage Friend list.
    *   Track "Times Together" (shared trips/events).
    *   Friendship status management.

### 3.5 History & Tracking
*   **Functionality**:
    *   `HistoryController`: View past activities and trips.
    *   `UserPlaceVisits`: Track how many times a user has visited a place.

### 3.6 Financials (In Development/Proposed)
*   **Entities**: `Expense`, `ExpenseShare`.
*   **Functionality**:
    *   Split bills/expenses within a trip (Rundown/Event).
    *   Track who paid and who owes money.
    *(Note: Found in migrations but not explicitly seen in main Routes yet)*

## 4. Data Model (Key Entities)

### Users
Standard Laravel users table with profile extensions.

### Rundowns
*   `id`, `title`, `description`, `date`, `status`, `is_public`, `metadata`, `created_by`.

### Activities
*   `id`, `rundown_id` (or `event_id`), `place_id`, `title`, `description`, `start_time`, `end_time`, `order_number`.

### Places
*   `id`, `name`, `address`, `latitude`, `longitude`, `category`, `description`.

### Friendships
*   `user_id`, `friend_id`, `status`, `times_together`.

## 5. Development Notes
*   **Migrations**: There is a potential overlap between `rundowns` (2025_10_14) and `events` (2025_12_14). The current codebase (`web.php`, `RundownController`) primarily uses **Rundowns**. The `events` schema might be a future direction or an alternative implementation.
*   **API**: Currently, the application follows a Monolithic structure (Server-side rendering with Blade), though some endpoints return JSON (`getMapData`, `getByDate`) for dynamic frontend interactions.
