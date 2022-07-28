![image](https://user-images.githubusercontent.com/54909696/144947502-ef90f2a8-efcb-415d-b30d-5eba9d56fa65.png)
# <p align="center">🟣 Project 7 : Create a webservice with an API 🟣</p>

[![SymfonyInsight](https://insight.symfony.com/projects/38ede997-7d21-4eb4-a56b-28b30ba6501a/big.svg)](https://insight.symfony.com/projects/38ede997-7d21-4eb4-a56b-28b30ba6501a)

## 🧩 Prerequisites

The project use the **Symfony 6** Framework and **PHP 8.0** or higher. You also need to have the latest version of **OpenSSL** installed.


## 📌️ Install steps

**1.** First you need to copy the repository by using `git clone https://github.com/ledukilian/LeduKilian_P7_07072022`

**2.** Use `composer install` command to install required packages

**3.** Copy the `.env` file located in the root folder to `.env.local` and fill `APP_ENV`, `DATABASE_URL` AND `MAIL_DSN` (you can use MailDev)

**4.** Generate the SSL Keys with AND `php bin/console lexik:jwt:generate-keypair`

If needed, you can install *symfony/web-server-bundle* and start the server with `php bin/console server:start`

## ⚙️ Database

**1.** Create database with `php bin/console doctrine:database:create`

**2.** Update the database schema with `php bin/console doctrine:schema:update --force`

You can use initial fixtures dataset with `php bin/console doctrine:fixtures:load`



## 🔐 Login
If you use the fixtures, you can use one of the 3 default company account :

- [ ] `admin@bilemo.fr` | `bilemo`
- [ ] `yves.atroloin@b-corp.fr` | `bilemo`
- [ ] `anna.rtichaud@acompany.fr` | `bilemo`

