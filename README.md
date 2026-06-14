## Task 7: Deployment Plan

**<ins>Understand the problem</ins>**
- This is a web app 
- The main objective is to receive leads and send notifications reliably
- Assuming the total frequency of users sending leads is low
- It only contains text

**<ins>Propose High-Level Design</ins>**\
There will be 2 primary APIs:
- Lead receiving API
- Email Notification sending API

The infrastructure for deployment is <u>AWS could services.</u>

The Postgres database will be hosted by <u>AWS Relational Database Service (RDS).</u>

The Laravel Queue workers will mainly use Redis for processing tasks/jobs due to is asynchronous nature, provided by <u>AWS ElastiCache.</u>

The environment configuration will be handled by a Virtual Machine via <u>AWS EC2 instances.</u>

The notification emails will be handled by <u>AWS Simple Email Service.</u>

For monitoring, logging or general observability, it will be handled by <u>AWS CloudWatch</u> to collect logs and metrics from each individual instance.

For routing API request, it will be handled using <u>AWS Route 53.</u>

For managing and distributing traffic loads in case a huge burst of request for data ingestion, it will be handled by <u>AWS Elastic Load Balancing.</u>

In case of a DDoS attack to overwhelm the EC2 instance, we can use <u>AWS Web Application Firewall</u> to implement rate-limiting mechanism.

Lastly, in case the deployment breaks, <u>AWS Backup</u> will ensure database is backed up every day at 1 a.m. to prevent data loss.

<img width="600" height="500" alt="image" src="https://github.com/user-attachments/assets/36f5a5f9-ef08-477b-b1f1-4742a72536c3" />

**<ins>Decisions</ins>**
1.	For task 2, I could directly modify the main “leads” table to insert “status” column since this is a local development. However, in this case, I have create a “add_status_to_leads_table” migration file to insert into the main “leads” table without altering existing test data. Making it easier for logic processing by decoupling it from main table.
2.	For task 5, creating admin interface requires 2 separate HTTP requests to fetch and update data. JSON and JavaScript were recommended by AI to have instant response to the POST update changes in the database. However, to keep the data manipulation logic in the controller component instead of in view, changes were made to accommodate this such as an update button for sending an explicit request instead of an instant update.
3.	For task 6, “unique_lead” concatenated field from lead table used for duplicate detection, was initially used to foreign key for audit table. But if “unique_lead” changes, it will affect audit table. So, it is safer reference back the leads “id” field instead.

**<ins>Tradeoffs</ins>**
1.	In task 3, since jobs are process via Laravel’s background worker, detecting and announcing duplicate leads are not possible because background worker “php artisan queue:work” runs continuously with infinite loop. It will eliminate detected duplicate (or problematic) jobs and keep running for new requests. So, due to background worker requirements, the duplicates will fail silently even if its processing logic is still in the controller interface.
2.	For task 6, status update function is separate from other fields due to different set of validation rules. While it’s possible to merge the two functions, this branching is fragile, and the intent of the code turns implicit rather than explicit. Harder to its code intent. 

## Setup Instructions

**<ins>Herd Setup</ins>**
Download Herd to manage dependencies for PHP versions and testing environment. Link: https://herd.laravel.com/windows

Follow this video for the complete setup guide for Herd. 
Link:https://www.youtube.com/watch?v=DKnn8TlJ4MA&list=PL4cUxeGkcC9gF5Gez17eHcDIxrpVSBuVt&index=1

Herd will allocate a dedicated folder for your projects.
<img width="975" height="298" alt="image" src="https://github.com/user-attachments/assets/9ce42934-2a6e-4840-b66a-ed70e29ff6ff" />

Since this project requires, PHP 8.3, installed it via Herd.
<img width="975" height="257" alt="image" src="https://github.com/user-attachments/assets/24585afa-e048-484c-9ee3-44b9dd3c0cc9" />

For a clean install of Laravel 11 dependencies, follow the steps below:
1.	Go to herd folder, open CLI, then type and run “composer create-project laravel/laravel:^11.0 okapi_project --ignore-platform-reqs”
2.	It will give you security errors and refuse to install dependencies cause its quite old, so open the project with VScode and find its “composer.json”
3.	Add the code below under “config”
'''json
"audit": {
    "abandoned": "ignore",
    "block-insecure": false
}
'''
4.	Open terminal in VScode and run “herd composer update” in CLI.

**<ins>Connecting to Postgres SQL Database</ins>**
To connect to PostgreSQL, change the settings in “.env” and “database.php” file according to your Postgres Database settings. Make sure to create your dedicated database in pgAdmin 4 first.

<img width="174" height="125" alt="Screenshot 2026-06-14 214724" src="https://github.com/user-attachments/assets/d07e663f-da99-47ac-beaf-66a43910d382" />
<img width="462" height="279" alt="image" src="https://github.com/user-attachments/assets/ed845f5f-ec15-41a6-9682-3e62bf4ffe1d" />
<img width="788" height="388" alt="image" src="https://github.com/user-attachments/assets/6df28cea-992c-40da-985e-791f3acd5177" />
<img width="797" height="609" alt="image" src="https://github.com/user-attachments/assets/b6e07095-d13b-447b-935c-3d8f2c1d5126" />

**<ins>Styling</ins>**
If tailwind functions like @apply does not run, make sure tailwind is installed in the Laravel environment by typing the command in the CLI below:

“npm install -D @tailwindcss/vite” 

And add the dependency with in the “vite.config.js” file
<img width="975" height="378" alt="image" src="https://github.com/user-attachments/assets/67d87f60-d457-4709-a6d3-674e583de29e" />

**<ins>Running the Project</ins>**
After running the previous steps, run “npm run dev” in the terminal of your VScode.

If its running successfully, Herd will provide ways to interact with the test site, like the image below:
<img width="1044" height="513" alt="image" src="https://github.com/user-attachments/assets/2429d5bc-bf57-45f3-866f-7469d2054674" />

If you click on the URL provided, it will open the test environment in your browser.
<img width="725" height="458" alt="image" src="https://github.com/user-attachments/assets/b188ef6f-bade-4d53-8adc-3c9816a548ad" />

Open a second terminal in your VScode and run “php artisan migrate:fresh --seed” to create the tables in the Postgres Database and populate it with fake data.

To check if the database is populated with fake data, run a few queries in pgAdmin 4 to confirm.

<img width="892" height="524" alt="image" src="https://github.com/user-attachments/assets/ec08f115-b3e7-4173-b7a3-1bdad087f8ef" />

Afterwords, run “php artisan queue:work” to run the background worker and receive any 3rd party data ingestion. 
Data ingestion can be tested using Postman. 
The image below an example of a background worker receiving requests successfully.
 

After these crucial setups are complete, you should be able to run this project after forking it from GitHub.



