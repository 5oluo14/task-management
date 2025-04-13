# Task Management Application

A simple yet powerful Task Management Application built with Laravel, Vue.js 3, and TailwindCSS.

## Features

- User authentication (Register/Login) via Laravel Sanctum
- Create, edit, delete, and list tasks
- Mark tasks as complete or incomplete
- Filter tasks by status (Completed, Pending)
- Clean and responsive UI with TailwindCSS
- Repository pattern for better code organization

## Tech Stack

### Backend

- Laravel 11
- Laravel Sanctum for API authentication
- Repository Design Pattern
- Form Request Validation
- API Resources for response formatting

### Frontend

- Vue.js 3 with Composition API
- TailwindCSS for styling
- Vue Router for SPA navigation
- Axios for API requests

## Project Structure

### Backend Architecture

- **Models**: User and Task with proper relationships
- **Controllers**: API controllers for Authentication and Task management
- **Repositories**: Implementation of the repository pattern
- **Requests**: Form request validation classes
- **Resources**: API resource classes for consistent response formatting
- **Routes**: API route definitions with Sanctum authentication

### Frontend Architecture

- **Composables**: Reusable composition functions for API integration
- **Components**: Reusable Vue components
- **Pages**: Main application pages
- **Router**: Vue Router configuration

## Installation

1. Clone the repository:

   ```bash
   git clone git@github.com:5oluo14/task-management.git
   cd task-management
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install JavaScript dependencies:

   ```bash
   yarn install
   ```

4. Create a copy of the `.env` file:

   ```bash
   cp .env.example .env
   ```

5. Generate an application key:

   ```bash
   php artisan key:generate
   ```

6. Configure your database in the `.env` file:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Run database migrations:

   ```bash
   php artisan migrate
   ```

8. Build frontend assets:

   ```bash
   yarn run dev
   ```

9. Start the development server:

   ```bash
   php artisan serve
   ```

10. Visit `http://localhost:8000` in your browser

## API Endpoints

### Authentication

- `POST /api/v1/auth/register`: User registration
- `POST /api/v1/auth/login`: User login
- `POST /api/v1/auth/logout`: User logout (requires authentication)

### Tasks

- `GET /api/v1/tasks`: Get all tasks (for the logged-in user)
- `POST /api/v1/tasks`: Create a task
- `GET /api/v1/tasks/{id}`: Get a specific task
- `PUT /api/v1/tasks/{id}`: Update a task
- `DELETE /api/v1/tasks/{id}`: Delete a task

## Usage

1. Register a new account or login with existing credentials
2. Once logged in, you'll be redirected to the dashboard
3. Create new tasks using the "Add Task" button
4. Mark tasks as completed by checking the checkbox
5. Edit tasks by clicking the edit button
6. Delete tasks by clicking the delete button
7. Filter tasks using the dropdown menu

## Security

- Laravel Sanctum is used for API authentication
- Form request validation ensures data integrity
- Authorization checks prevent users from accessing or modifying other users' tasks
- CSRF protection is enabled for all web routes
