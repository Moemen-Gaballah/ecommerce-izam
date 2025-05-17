# Izam ecommerce
The system acts as ecommerce


## Installation

Clone the repo `git clone https://github.com/Moemen-Gaballah/ecommerce-izam.git` and `cd` into it

`composer install`

Rename or copy `.env.example` file to `.env`

`php artisan key:generate`

Set your database credentials in your `.env` file

`composer install`

`php artisan migrate --seed`

`php artisan serve`


`http://127.0.0.1:8000/`

End point  `http://127.0.0.1:8000/api`

Email/Password: `moemen@example.com/password`

Postman collection in the root folder


## Installation Frontend

`cd` into public and ecommerce-frontend

`npm install`

`npm start`


### Done Backend

- [x] API Login
- [x] API my profile
- [x] API Logout
- [x] API get products.
- [x] API Cache for products
- [x] API Store order
- [x] API Get order.
- [x] API Show Order .
- [x] Eloquent, Validation, Migrations, Seeders for products and user.
- [x] basic search and efficient pagination.
- [x] Laravel Sanctum.
- [x] Implement events and listeners.


### Done frontend
- [x] Login Page
- [x] Products Page
- [x] Store Order
- [x] List Orders
- [x] Use Material UI, React functional components like navbar.
- [x] Organize code cleanly.


### TODO Backend
- [] Seperate logic from controller to service or actions.
- [] use trait APIResponse for all response.
- [] refactor filter and search for products.
- [] Docker file and Docker compose
- [] Update migrations products add image, created_by, soft deleted, ... 
- [] Update migrations order to store price and discount ...
- [] Store Products
- [] Unit test
- etc...


### TODO Frontend
- [] Complete UI and solve some issues
- [] use middleware for authentication.
- [] Docker file and Docker compose
- [] Unit test
- etc...


### 📌 Notice

I received this task on **16-05-2025** at the end of the day and had some prior commitments on Friday.  
I managed to submit the initial version today, **17-05-2025**.

Unfortunately, I won’t have enough time during the remaining two days before the deadline to continue working on it.  
However, I’m planning to dedicate some time over the upcoming weekend, **insha'Allah**, to enhance and finalize the work if you want let me know please.

If there are any concerns regarding, please feel free to share them, and kindly allow me some time to address and improve any points as needed.

Thank you!
