## Task 7: Deployment Plan

**<ins>Understand the problem</ins>**
- This is a web app 
- The main objective is to receive leads and send notifications reliably
- Assuming the total frequency of users sending leads is low
- It only contains text

**<u>Propose High-Level Design</u>**
There will be 2 primary APIs:
- Lead receiving API
- Email Notification sending API

The infrastructure for deployment is <u>AWS could services.</u>\
The Postgres database will be hosted by <u>AWS Relational Database Service (RDS).</u>\
The Laravel Queue workers will mainly use Redis for processing tasks/jobs due to is asynchronous nature, provided by <u>AWS ElastiCache.</u>\
The environment configuration will be handled by a Virtual Machine via <u>AWS EC2 instances.</u>\
The notification emails will be handled by <u>AWS Simple Email Service.</u>\
For monitoring, logging or general observability, it will be handled by <u>AWS CloudWatch</u> to collect logs and metrics from each individual instance.\
For routing API request, it will be handled using <u>AWS Route 53.</u>\
For managing and distributing traffic loads in case a huge burst of request for data ingestion, it will be handled by <u>AWS Elastic Load Balancing.</u>\
In case of a DDoS attack to overwhelm the EC2 instance, we can use <u>AWS Web Application Firewall</u> to implement rate-limiting mechanism.\
Lastly, in case the deployment breaks, <u>AWS Backup</u> will ensure database is backed up every day at 1 a.m. to prevent data loss.\

<img width="600" height="500" alt="image" src="https://github.com/user-attachments/assets/36f5a5f9-ef08-477b-b1f1-4742a72536c3" />

**<u>Decisions</u>**
1.	For task 2, I could directly modify the main “leads” table to insert “status” column since this is a local development. However, in this case, I have create a “add_status_to_leads_table” migration file to insert into the main “leads” table without altering existing test data. Making it easier for logic processing by decoupling it from main table.
2.	For task 5, creating admin interface requires 2 separate HTTP requests to fetch and update data. JSON and JavaScript were recommended by AI to have instant response to the POST update changes in the database. However, to keep the data manipulation logic in the controller component instead of in view, changes were made to accommodate this such as an update button for sending an explicit request instead of an instant update.
3.	For task 6, “unique_lead” concatenated field from lead table used for duplicate detection, was initially used to foreign key for audit table. But if “unique_lead” changes, it will affect audit table. So, it is safer reference back the leads “id” field instead.

**<u>Tradeoffs</u>**
1.	In task 3, since jobs are process via Laravel’s background worker, detecting and announcing duplicate leads are not possible because background worker “php artisan queue:work” runs continuously with infinite loop. It will eliminate detected duplicate (or problematic) jobs and keep running for new requests. So, due to background worker requirements, the duplicates will fail silently even if its processing logic is still in the controller interface.
2.	For task 6, status update function is separate from other fields due to different set of validation rules. While it’s possible to merge the two functions, this branching is fragile, and the intent of the code turns implicit rather than explicit. Harder to its code intent. 

