# 💸 Income & Expense Tracker – Laravel VILT Stack

This is a **PHP-based income and expense tracking script**, built using the **Laravel 12** framework and the **VILT stack** (Vue, Inertia.js, Laravel, Tailwind CSS). The project implements a clean and extendable architecture for managing financial records including **income, expenses, categories, commissions**, and **detailed reports**.

> 📌 Inspired by a project idea featured on [Laravel Daily](https://laraveldaily.com/) by **Povilas Korop**.

---

## 🚀 Features

* ✅ Add and manage **income** records
* ✅ Add and manage **expense** records
* ✅ Link **expenses to income categories**
* ✅ Create **categories and sub-categories** for both income and expenses
* ✅ Add **commission entries** to income and expenses
* ✅ Generate **detailed reports**:

  * Daily
  * Weekly
  * Monthly
  * Annual
  * Custom date ranges
* ✅ Secure **user model with login**, allowing users to view and manage their own financial entries

---

## 🛠️ Built With

* **Laravel 12** (Backend)
* **Vue 3** (Frontend)
* **Inertia.js** (Frontend/backend glue)
* **Tailwind CSS** (Styling)
* **Laravel Breeze (VILT Starter Kit)** for auth and scaffolding

---

## 📦 Installation

```bash
git clone https://github.com/your-username/income-expense-tracker.git
cd income-expense-tracker

# Install dependencies
composer install
npm install && npm run dev

# Set up environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate

# Run server
composer run dev
```

---

## 🧪 Sample Credentials (if using demo)

```
Email: demo@example.com
Password: password
```

---

## 📊 Screenshots

Coming soon...

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!
Please feel free to submit a PR or open an issue.

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 🙏 Credits

Thanks to [Povilas Korop](https://laraveldaily.com/) for the project idea and inspiration.

