# 𝓤𝓷𝓲𝓗𝓾𝓫 𝓜𝓾𝓵𝓽𝓲-𝓟𝓾𝓻𝓹𝓸𝓼𝓮 𝓒𝓸𝓸𝓹𝓮𝓻𝓪𝓽𝓲𝓿𝓮
A web-based cooperative management system. UniHub Cooperative allows members to manage accounts, track savings and fixed deposits, monitor transactions, and generate reports through a user-friendly dashboard. Built with PHP, MySQL, HTML/CSS, and JavaScript.

<strong> ⋆୨୧˚  link  ✧˖°｡ </strong><br>
https://deguzman-project-multipurpose.infinityfreeapp.com/?i=1



## ₊⊹ Description

To design and implement a secure, efficient, and user-friendly online system to  
manage the accounts receivable processes of a multipurpose cooperative. The system  
will streamline invoicing, payment tracking, and reporting, offering transparency and  
accessibility to both administrators and members.




## ₊⊹ System Highlights

✦ User Management  <br>
✦ Accounts Receivable Management  <br>
✦ Financial Reporting  <br>
✦ Notifications and Alerts  <br>
✦ Data Security and Backup<br>




## Project structure

```text
public/             Web document root; existing PHP pages and action endpoints
  assets/css/       Shared stylesheets
  assets/images/    SVG and image assets
  uploads/          Runtime uploads (ignored by Git)
includes/db.php     Shared database connection
config/             Database settings and ignored local overrides
database/sample.sql Database import
scripts/lint.sh     PHP syntax checks
```

Page filenames remain unchanged so links, forms, and redirects continue to work
relative to the web document root. Pages still contain their existing application
logic and markup.

## Installation

1. Install PHP with the `mysqli` extension and MySQL/MariaDB (or XAMPP).
2. Clone this repository and create a MySQL database named `unihubdb`.
3. Import `database/sample.sql` into that database using phpMyAdmin or the MySQL CLI.
   The dump contains sample records; review them before using a shared database.
4. Copy `config/database.example.php` to `config/database.local.php` and set your
   database credentials. The local file is ignored by Git. Alternatively set
   `DB_HOST`, `DB_USER`, `DB_PASSWORD`, and `DB_NAME`; environment variables override
   local settings.
5. For local development, run from the repository root:

   ```sh
   php -S localhost:8000 -t public
   ```

   Open http://localhost:8000. For Apache/XAMPP, configure the site's DocumentRoot
   to the project's `public/` directory. On shared hosting, put the contents of
   `public/` in the web root and keep `config/` and `includes/` one level above it.
   Keep the SQL dump outside the web root.
6. Ensure `public/uploads/` is writable by the PHP process for payment proofs.

Run syntax checks with `sh scripts/lint.sh`.

## ₊⊹ Contribution Guidelines

Contributions are welcome! To contribute:<br>
✦   Fork the repository<br>
✦   Create a new branch (git checkout -b feature/YourFeature)<br>
✦   Make your changes and commit (git commit -m 'Add some feature')<br>
✦   Push to the branch (git push origin feature/YourFeature)<br>
✦   Open a pull request for review<br>
    





## ₊⊹ Reminder

Hello ˙ᵕ˙ This is one of my final requirements in Univ and is published to personally showcase my work. 
Please do not re-upload or copy this code for educational assignments without my knowledge (·•᷄ࡇ•᷅ ). Thank you!


## Development note

The application uses procedural PHP. The directory layout separates public files,
configuration, and database resources; a future refactor can extract shared page
layouts and feature logic incrementally.
