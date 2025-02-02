# Laravel POS System

## Project Setup

### Prerequisites
Ensure you have the following installed:
- PHP 8.1 or later
- Composer
- Node.js & npm
- MySQL or PostgreSQL (as per your environment)

### Installation Steps
1. **Clone the repository**
   ```sh
   git clone https://github.com/salim-hosen/agpos
   cd agpos
   ```

2. **Install dependencies**
   ```sh
   composer install
   npm install
   ```

3. **Set up the environment file**
   ```sh
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials.

4. **Generate application key**
   ```sh
   php artisan key:generate
   ```

5. **Run database migrations and seeders**
   ```sh
   php artisan migrate
   ```

6. **Build the frontend**
   ```sh
   npm run build
   ```

7. **Start the backend server**
   ```sh
   php artisan serve
   ```

8. **Start Vite development server (optional, if using Vite)**
   ```sh
   npm run dev
   ```

## API Documentation
The API is documented using Swagger.
- Access the documentation at:
  ```
  http://127.0.0.1:8000/api/documentation
  ```

## License
This project is licensed under the MIT License.

