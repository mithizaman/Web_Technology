# Book Shop

The **Book Shop** is a web-based application that allows users to loan and manage books in a library. It includes features for book loaning, token validation and book inventory management.

---

## Features

- Loan books using valid tokens.
- Manage book inventory: add, update and delete books
- Validate user inputs (e.g., names, student IDs, ISBNs etc.)
- Ensure tokens are valid and unused for book loaning
- Set loaning rules (e.g., maximum loan period of 10 days)
- Prevent duplicate loaning of the same book by the same user (using cookies)
- Display loaning details to users upon successful operations

---

## File Structure

```
📂 Book Shop
├── dbConnection.php          # Database connection script
├── process.php               # Core logic for loaning, adding, updating, and deleting books
├── token.json                # JSON file storing valid and used tokens
├── style.css                 # CSS file for styling the system
├── index.php                 # Main entry point for the system
└── book_shop.sql             # SQL file for setting up the database schema and tables
```

---

## Requirements

- **PHP**: Version 7.4 or later
- **MySQL**: For storing book inventory and loaning records
- **Web Server**: Apache or Nginx

---

## Usage

### Loaning a Book
1. Enter the student's full name and ID
2. Select the book to loan
3. Provide a valid token and specify the loan period and return date
4. The system validates inputs, checks token usage, and processes the request

### Managing Books
- **Add a New Book**: Fill out the book details and submit the form
- **Update a Book**: Select an existing book, modify its details, and save changes
- **Delete a Book**: Select a book and remove it from the database

---

## Validation Rules

- **Student Name**: Must contain only letters and hyphens
- **Student ID**: Must follow the format `XX-XXXXX-X` (e.g., `22-47903-2`)
- **Book Title/Author/Category**: Must contain only letters and hyphens
- **ISBN**: Must be a 13-digit number
- **Book Quantity**: Must be a positive integer

---

## Token Management

- Tokens are stored in `token.json` with two lists:
  - **Available Tokens**: Tokens that can be used for loaning.
  - **Used Tokens**: Tokens that have already been utilized.

---

## Author

**Basharul Alam Mazu**  
- LinkedIn: [linkedin.com/in/basharul-alam-mazu](https://linkedin.com/in/basharul-alam-mazu)  
- Email: [basharulalam6@gmail.com](mailto:basharulalam6@gmail.com)  
- Website: [basharulalammazu.github.io](https://basharulalammazu.github.io)
