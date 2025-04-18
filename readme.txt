## Sports Club Website - Installation Guide

### Step 1: Import Database
1. Unzip the downloaded files.
2. Locate the folder named `clubsports` inside the extracted files.
3. Open **phpMyAdmin** in your web browser (usually accessible via `http://localhost/phpmyadmin/`).
4. Create a new database named **clubsports**.
5. Click on the **clubsports** database and then select **Import**.
6. Choose the SQL file inside the `clubsports` folder and import it.
7. Once the import is successful, proceed to the next step.

### Step 2: Configure Database Connection
1. Navigate to the **sports/includes/** folder.
2. Locate the file **config.php**.
3. Open **config.php** in a text editor (e.g., Notepad++, VS Code).
4. Update the database credentials according to your setup:
   ```php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root'); // Change if necessary
   define('DB_PASSWORD', ''); // Add password if applicable
   define('DB_NAME', 'clubsports');
   ```
5. Save and close the file.

6. Now, navigate to the **sports/admin/includes/** folder.
7. Locate the **config.php** file and update it with the same database credentials as above.
8. Save and close the file.

### Step 3: Start the Website
1. Open a web browser.
2. Go to: `http://localhost/sportsclub/sports%20/index.php`

### Step 4: Access Admin Panel
1. Open a web browser.
2. Go to: `http://localhost/sportsclub/sports%20/admin/`
3. Use the following credentials to log in:
   - **Username:** admin
   - **Password:** Test@123

### Notes:
- Ensure you have **XAMPP** or **WAMP** installed and running.
- Start **Apache** and **MySQL** services before accessing the website.
- If encountering database connection errors, verify the database credentials in `config.php`.
- Make sure the `clubsports` database is properly imported into **phpMyAdmin**.

For any issues, please check your **Apache/PHP logs** or database configuration.

