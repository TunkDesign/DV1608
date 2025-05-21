![DV1608 Symfony Project](https://i.imgur.com/WQiomwj.png)

<center>

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/TunkDesign/DV1608/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/TunkDesign/DV1608/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/TunkDesign/DV1608/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/TunkDesign/DV1608/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/TunkDesign/DV1608/badges/build.png?b=main)](https://scrutinizer-ci.com/g/TunkDesign/DV1608/build-status/main)
</center>
<br>

# DV1608 – Symfony Website

This is a school project for the course DV1608. The application is built using Symfony and follows the MVC architecture. It emphasizes clean structure, security, and responsive design.

## Features

- Symfony 7.2.5
- Twig as templating engine
- Routing, Controllers, Views, and Models
- Basic porfolio and API documentation

## Getting Started

Follow these steps to clone and run the website locally.

### 1. Clone the repository

```bash
git clone https://github.com/TunkDesign/DV1608.git
cd DV1608
```

### 2. Install dependencies

```bash
composer install
```

### 3. Start the development server

```bash
symfony server:start
```

Or with built-in PHP server:

```bash
php -S 127.0.0.1:8000 -t public
```

## Requirements

- PHP 8.3 or higher
- Composer
- Symfony CLI (optional but recommended)

## Directory Structure (brief)

```
├── config/         # Configuration files
├── public/         # Public files (index.php, assets)
├── src/            # PHP code (controllers, entities, etc.)
├── templates/      # Twig templates
└── .env            # Environment variables
```

## Author

- Karl Håkansson – [TunkDesign](https://github.com/TunkDesign)