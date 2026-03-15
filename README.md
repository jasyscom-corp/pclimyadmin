# PCLIAMYADMIN

[![GitHub Stars](https://img.shields.io/heavy/github/stars/jasyscom-corp/pcliamyadmin?style=flat-square)](https://github.com/jasys.com-corp/pcliamyadmin) ![GitHub License](https://img.shields.io/github/license/jasyscom-corp/pcliamyadmin?style=flat-square)

A lightweight interactive CLI database manager for MySQL/MariaDB written in PHP. PCLIAMYADMIN provides a terminal-based interface for managing databases and tables without needing a web interface.

## Overview

PCLIAMYADMIN allows you to interact with your database server using a structured menu system instead of manually writing SQL queries. The interface is fully terminal-based and designed to be easy to navigate using simple numeric menus.

Perfect for:
- ✅ Server administration
- ✅ SSH sessions
- ✅ Development environments
- ✅ Termux on Android
- ✅ Remote database management

## Features

### Database Management [✅]
- List all available databases
- Create new databases
- Delete databases
- Database information and statistics
- Export/Import entire databases via SQL dump

### Table Management [✅]
- List tables in current database  
- Create tables with custom columns
- Drop tables
- Describe table schema
- Table information (row count, size, engine)
- Browse table data with pagination
- Advanced table customization (filtering, sorting, formatting)

### Data Management [✅]
- Insert rows interactively
- Update existing rows
- Delete rows
- Search through table data
- Raw SQL query execution
- Query history (15 most recent queries)

### Import/Export Features [✅]
- Backup entire databases
- Restore from backup files
- Export tables in multiple formats (SQL, CSV, JSON, TXT)
- Import data from external files
- Selective structure/data export options

### Advanced Tools [✅]
- Index manager for performance optimization
- Trigger and view management
- Performance monitoring
- Security audit functions
- Custom query storage

### User Interface [✅]
- Clean, colored terminal output
- Breadcrumb navigation
- Active database indicator
- Status and information panel
- Keyboard navigation support

## Quick Start

### Prerequisites

- PHP CLI (version 7.4 or higher)
- MySQL or MariaDB server
- Terminal access (Linux, Unix, or compatible)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/jasyscom-corp/pcliamyadmin.git
   cd pcliamyadmin
