# On-Road Breakdown Assistance

## Project Overview

On-Road Breakdown Assistance is a comprehensive web application designed to connect vehicle owners with mechanics during roadside emergencies. The platform facilitates quick and efficient communication between clients experiencing vehicle breakdowns and qualified mechanics who can provide timely assistance.

## Key Features

- **User Authentication System**: Secure login and registration for three user types (Clients, Mechanics, and Administrators)
- **Request Management**: Clients can submit breakdown assistance requests with location and problem description
- **Mechanic Assignment**: Mechanics can view and accept service requests in their area
- **Solution Documentation**: Mechanics can document the solutions provided for each breakdown
- **Feedback System**: Clients can rate and review mechanic services
- **Admin Dashboard**: Administrators can monitor all activities, manage users, and generate reports
- **Real-time Status Updates**: Clients can track the status of their requests in real-time
- **Reporting System**: Generate detailed reports for completed service requests

## Technologies Used

### Backend
- **PHP**: Core server-side programming language
- **MySQL**: Relational database management system
- **MVC Architecture**: Custom implementation for organized code structure

### Frontend
- **HTML5/CSS3**: Structure and styling
- **Bootstrap 5**: Responsive design framework
- **JavaScript**: Client-side interactivity

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/On-Road.git
   ```

2. Import the database:
   - Navigate to phpMyAdmin
   - Create a new database named 'on-road'
   - Import the SQL file from 'Database Configuration/on-road.sql'

3. Configure database connection:
   - Open 'Controllers/DbController.php'
   - Update the database credentials if necessary

4. Place the project in your web server's document root (e.g., htdocs for XAMPP)

5. Access the application through your web browser:
   ```
   http://localhost/On-Road/
   ```

## User Roles

### Client
- Register and login to the system
- Submit breakdown assistance requests
- Track request status
- Provide feedback on completed services

### Mechanic
- View and accept service requests
- Document solutions for vehicle problems
- View client feedback and ratings

### Administrator
- Manage all users (clients and mechanics)
- Monitor all service requests
- Generate and download reports
- Assign mechanics to requests manually if needed

## Project Structure

- **Controllers/**: Contains all controller classes for handling business logic
- **Models/**: Contains all data models and database interactions
- **Views/**: Contains all user interface files
- **Database Configuration/**: Contains database schema and setup files
- **root/**: Contains static assets (CSS, JavaScript, images)
