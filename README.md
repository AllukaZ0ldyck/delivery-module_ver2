Step-by-step setup (beginner-friendly)

Open a terminal (PowerShell on Windows, Terminal on macOS, or WSL on Windows).

Clone the repo

# HTTPS (easy, no SSH key needed)
git clone https://github.com/AllukaZ0ldyck/delivery-module_ver2.git

# OR SSH (if you set up SSH keys)
git clone git@github.com:AllukaZ0ldyck/delivery-module_ver2.git


Enter the project folder

cd delivery-module_ver2


Install PHP dependencies (Composer)

composer install


If Composer asks to create vendor folder or needs auth tokens, follow prompts. If you get memory errors, run:

COMPOSER_MEMORY_LIMIT=-1 composer install


Copy the example environment file

# macOS / Linux
cp .env.example .env

# Windows (PowerShell)
copy .env.example .env


Edit .env to set your environment variables
Open .env in a text editor and set your DB settings (example):

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password


If you don’t have a DB yet, create one (e.g., in MySQL: CREATE DATABASE your_db_name;).

Generate the application key

php artisan key:generate


This sets APP_KEY in your .env.

Run database migrations (if the project has migrations)

php artisan migrate
# optionally run seeders if available
php artisan db:seed


If you prefer to refresh everything (danger: deletes data): php artisan migrate:fresh --seed

Install Node packages and compile front-end assets

npm install
# for development (fast, hot reload)
npm run dev

# or for production build
npm run build


If npm run dev fails with an OpenSSL / "digital envelope routines" error on Node 17+, set:

macOS / Linux:

export NODE_OPTIONS=--openssl-legacy-provider
npm run dev


Windows (PowerShell):

$env:NODE_OPTIONS="--openssl-legacy-provider"
npm run dev


Create storage symlink (for public uploaded files)

php artisan storage:link


Set filesystem / cache permissions (Linux)

sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache


(Adjust www-data for your web user if different.)

Run the local dev server

php artisan serve


Then open http://127.0.0.1:8000 in your browser.
