PCLIAMYADMIN

PCLIAMYADMIN is a lightweight interactive CLI database manager for MySQL/MariaDB written in PHP.
It provides a terminal-based interface for managing databases and tables without needing a web interface.

The goal of this project is to offer a simple phpMyAdmin-like experience directly in the terminal, making it useful for environments like servers, SSH sessions, or mobile terminals such as Termux.

---

Overview

PCLIAMYADMIN allows you to interact with your database server using a structured menu system instead of manually writing SQL queries.

The interface is fully terminal-based and designed to be easy to navigate using simple numbered menus.

---

Features

Database Management

- List all available databases
- Select active database
- View server connection information
- Interactive menu navigation

Table Management

- List tables inside a database
- Create new tables
- Alter table structure
- Drop tables
- Describe table schema
- Browse table data

Data Management

- Insert rows into tables
- View table data in a formatted grid
- Interactive selection for tables and fields

SQL Console

- Execute raw SQL queries
- Display results in table format
- Useful for advanced operations not yet supported in the menu

CLI Interface

- Structured menu system
- Clear screen header
- Active database indicator
- Status and information panel
- Simple numeric navigation

---

Example Interface

================================================
          PCLIAMYADMIN BY JASYSCOM
================================================
SERVER: 127.0.0.1:3306
------------------------------------------------
MENU: DATABASE MANAGER
ACTIVE DB: jasysdb
------------------------------------------------
INFO: Using database jasysdb
------------------------------------------------

---

Requirements

- PHP CLI
- MySQL or MariaDB server
- Linux / Unix environment
- Terminal access

Tested environments:

- Linux
- SSH servers
- Termux on Android

---

Installation

Clone the repository:

git clone https://github.com/jasyscom-corp/pcliamyadmin.git
cd pcliamyadmin

Run the program:

php commands.php

---

Configuration

The application stores connection settings in:

config.json

This file contains:

- database host
- port
- username
- password

Example:

{
  "host": "127.0.0.1",
  "port": "3306",
  "username": "root",
  "password": "yourpassword"
}

---

Project Structure

pcliamyadmin
│
├── commands.php
├── processors.php
├── connections.php
├── configurators.php
├── config.json
└── README.md

File Responsibilities

commands.php

Main CLI navigation and menu system.

processors.php

Core database operations and UI rendering.

connections.php

Handles database connection logic.

configurators.php

Manages configuration loading and validation.

---

Design Goals

The project focuses on:

- simplicity
- minimal dependencies
- portability
- easy deployment

Unlike web-based tools, PCLIAMYADMIN runs entirely in the terminal and requires no web server.

---

Future Feature Ideas

Planned improvements and ideas:

Database Features

- Create and drop databases
- Database export / import
- Database size information

Table Features

- Table rename
- Table indexing manager
- Foreign key management

Data Features

- Update row wizard
- Delete row wizard
- Pagination for large tables
- Search / filter rows

CLI Improvements

- Colored terminal output
- Keyboard navigation
- Better table rendering
- Breadcrumb navigation
- Query history

Advanced Tools

- Backup manager
- Migration tools
- Plugin system
- Multi-database session support

---

Security Notes

PCLIAMYADMIN stores credentials locally in "config.json".
This tool is intended for local environments or trusted servers.

For production environments consider:

- using environment variables
- limiting database privileges
- protecting configuration files

---

Contributing

Contributions are welcome.

You can help by:

- fixing bugs
- improving UI/UX
- adding new database features
- improving documentation

Fork the repository and submit a pull request.

---

Author

Jasyscom

GitHub
https://github.com/jasyscom-corp

---

License

This project is currently released as an open-source tool.
License details can be added in future versions.
