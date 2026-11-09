# LiberAnalytics

*Freeing analytics from the ordinary.*

[LiberAnalytics.com](https://liberanalytics.com) is a technical blog exploring data analytics concepts and implementations through working code examples and explanations.

The central theme is solving real-world data challenges creatively by leveraging existing infrastructure, technologies, and data artifacts. Rather than introducing new platforms or tools for every challenge, LiberAnalytics explores practical approaches to extending and integrating existing systems.

## Technology

LiberAnalytics.com is built primarily with native web technologies, without relying on application frameworks. The technology stack includes HTML, CSS, JavaScript, PHP, Python, PostgreSQL, and Bash scripting on Ubuntu Linux.

Infrastructure and hosting are managed through Amazon Web Services (AWS), using EC2 instances running Ubuntu Linux for the web and database servers, and Amazon Route 53 for DNS management.

## Repository Structure

The repository is organized into four primary directories: `site/`, `src/`, `sql/`, and `python/`.

### `site/` — Presentation Layer

Contains the front-end artifacts used to render content on LiberAnalytics.com, including HTML, CSS, JavaScript, and image assets. This directory also contains the publicly accessible API endpoints used by the website.

### `src/` — Application Backend

Contains the PHP backend responsible for the deterministic data pipeline that retrieves and processes blog content from the underlying PostgreSQL data store. It includes reusable PHP classes, application logic, and internal API implementations not directly exposed to website visitors.

### `sql/` — Database Definitions and Operations

Organized into three principal components:

- **DDL (Data Definition Language):** Defines the LiberAnalytics database structure, including schemas, tables, roles, and PostgreSQL functions used for data retrieval and processing.
- **DML (Data Manipulation Language):** Contains seed data and SQL queries dynamically invoked by the PHP backend.
- **Scripts:** Contains database initialization and supporting execution scripts.

### `python/` — Python Integration Examples

Contains a configuration script demonstrating the use of AWS Key Management Service (KMS) to encrypt database credentials, along with a PostgreSQL connection example featured in the [Python to PostgreSQL](https://liberanalytics.com/pythonpostgreconnect) article.

## Website

[**LiberAnalytics.com**](https://liberanalytics.com)