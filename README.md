# ICS-499-51-Dance-Website-Dolphin-Team
A website displaying various Brazil dances.

## How to run locally:
1. Ensure XAMPP and [Composer](https://getcomposer.org/download/) are installed
2. Place project directory within C:\xammp\htdocs, or linux equivalent
3. In a command line, run `composer update` to get dependencies
4. Launch MySQL and use database_creation.sql to build database, ensure the port for MySQL on XAMPP is the same in database.php
5. Launch Apache server module
6. Go to http://localhost/[local repository name]/public/


## How to run chatbot
1. Install Node.js 
2. Create a .env file in the root project folder, enter the key on one line in the file as GROQ_API_KEY=[key]
3. Run the chatbot.js file directly or run in the terminal by entering node chatbot.js
