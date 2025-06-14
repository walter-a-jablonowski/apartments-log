# Apartment Log

A mobile-optimized web application for tracking your apartment search process. This application allows you to log both apartments you're interested in and common activities related to your search.

## Features

- Track apartments with detailed information
  - Status tracking (new, current, maybe, done)
  - Add multiple status updates with dates
  - Store apartment details, results, and URLs
  - Take photos using your smartphone camera
- Log common activities related to your search
- Mobile-optimized interface with Bootstrap 5.3
- Data stored in YAML format
- Full CRUD functionality for all entry types

## Setup

1. Make sure you have PHP installed (7.4+ recommended)
2. Install Composer dependencies:
   ```
   composer install
   ```
3. Ensure the data directory is writable by the web server
4. Access the application through a web server

## Usage

### Home Page
- View all entries sorted by date (newest first)
- Quick access to create new entries

### Adding a New Apartment
1. Click "New Entry" > "New Apartment"
2. Fill in the required details:
   - Date
   - Title
   - Details (optional)
   - Status (new, current, maybe, done)
   - Result (optional)
   - URL (optional)
3. Use the camera feature to take photos
4. Click "Save"

### Adding Status Updates to an Apartment
1. Edit an existing apartment entry
2. Click "Add Status Update"
3. Enter date (optional) and status text
4. Click "Save"

### Adding a Common Activity
1. Click "New Entry" > "New Common Activity"
2. Fill in the required details:
   - Date
   - Title
   - Text
3. Click "Save"

## Data Storage

All data is stored in `/data/data.yml` in YAML format. Photos are stored in `/data/files/[FILES_ID]/`.

## Mobile Features

The application is optimized for mobile use with:
- Responsive design
- Mobile-friendly navigation
- Camera integration for taking photos
- Touch-friendly interface elements
