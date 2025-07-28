# Income and Expense Management System

A comprehensive Laravel-based web application for managing personal or business income and expenses with detailed reporting and analytics.

## Features

- **Income and Expense Tracking**: Add, edit, and delete income and expense transactions
- **Category Management**: Create and manage categories and sub-categories for both income and expenses
- **Commission Tracking**: Record commissions for both income and expense transactions
- **User Authentication**: Secure login system with user-specific data
- **Comprehensive Reporting**: Detailed reports with daily, weekly, monthly, annual, and custom date range views
- **Analytics Dashboard**: Visual breakdowns of income vs expenses, category analysis, and trends
- **Modern UI**: Clean, responsive interface built with Vue.js and Tailwind CSS

## Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue.js 3 with Inertia.js
- **Styling**: Tailwind CSS
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Breeze with Inertia

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- Database (MySQL, PostgreSQL, or SQLite)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd income-expense
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=income_expense
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## Testing the Application

You can test the application using the following demo credentials:

- **Email**: admin@gmail.com
- **Password**: 12345678

These credentials will give you access to a pre-configured account with sample data to explore all features of the system.

## Usage

### Getting Started

1. Navigate to the application URL (typically `http://localhost:8000`)
2. Log in using the test credentials or register a new account
3. Start by creating categories for your income and expenses
4. Add your first transactions
5. Explore the reporting features

### Key Features

**Transaction Management**
- Add income and expense transactions
- Set amounts, dates, and categories
- Record commissions for transactions
- Edit or delete existing transactions

**Category System**
- Create parent categories (e.g., "Food", "Transportation")
- Add sub-categories (e.g., "Groceries" under "Food")
- Categorize as income or expense types
- Enable/disable categories as needed

**Reporting and Analytics**
- View summary statistics (total income, expenses, net amount)
- Analyze trends over different time periods
- Category breakdown analysis
- Top income and expense categories
- Custom date range reporting

## Project Structure

```
income-expense/
├── app/
│   ├── Enums/           # PHP enums for types
│   ├── Http/
│   │   ├── Controllers/ # Application controllers
│   │   ├── Requests/    # Form request validation
│   │   └── Middleware/  # Custom middleware
│   └── Models/          # Eloquent models
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── resources/
│   └── js/
│       ├── components/  # Vue.js components
│       ├── pages/       # Inertia.js pages
│       └── layouts/     # Page layouts
└── routes/              # Application routes
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue in the repository or contact the development team.

## Changelog

### Version 1.0.0
- Initial release
- Complete income and expense management system
- User authentication and authorization
- Comprehensive reporting and analytics
- Modern Vue.js frontend with Tailwind CSS 
