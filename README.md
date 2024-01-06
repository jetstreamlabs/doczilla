<div style="width:1000px;margin:2rem auto 0 auto;">
  <img src="https://raw.githubusercontent.com/jetstreamlabs/doczilla/main/.github/img/logo.png" alt="Doczilla!" />
</div>

<div>
    <img src="https://github.com/jetstreamlabs/doczilla/actions/workflows/tests.yml/badge.svg" alt="Build Status" />
    <img src="https://raw.githubusercontent.com/jetstreamlabs/doczilla/main/.github/img/coverage-badge.svg" alt="Code Coverage" />
    <img src="https://img.shields.io/packagist/dt/doczilla/doczilla" alt="Total Downloads" />
    <img src="https://img.shields.io/packagist/v/doczilla/doczilla" alt="Latest Stable Version" />
    <img src="https://img.shields.io/packagist/l/doczilla/doczilla" alt="License" />
    <img src="https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2Fa2df8a1a-95ab-444f-baa4-bd511a0149f7%3Fcommit%3D1&style=flat" alt="Laravel Forge Site Deployment Status" />
  </div>

## Doczilla Documentation Platform

Pending our wip installer package you can follow the directions below to get set up.

## Local Development

To get started you'll need node and npm installed on your system, along with composer of course. Since this is a Laravel based platform we're assuming you'll be developing locally on Valet. If not, then you'll know where to go to clone your repos.

1. Fork this repository
2. Open your terminal and `cd` to your `~/Sites` folder
3. Clone your fork into the `~/Sites/doczilla` folder, by running the following command _with your username placed into the {username} slot_:
   ```bash
   git clone git@github.com:{username}/doczilla.git doczilla
   ```
4. CD into the new directory you just created:
   ```bash
   cd doczilla
   ```
5. Run the `setup` script located in the `package.json` file, which will take all the steps necessary to prepare your local install:
   ```bash
   npm run setup
   ```
6. Alternately you can run the `docs:setup` artisan command directly preceeded by `composer install`:

```bash
composer install && php artisan docs:setup
```

### Syncing Upstream Changes Into Your Fork

This [GitHub article](https://help.github.com/en/articles/syncing-a-fork) provides instructions on how to pull the latest changes from this repository into your fork.

### Updating After Remote Code Changes

If you pull down the upstream changes from this repository into your local repository, you'll want to update your Composer and NPM dependencies, as well as update your documentation branches. For convenience, you may run the `update` script in `package.json` which aliases to running the artisan `docs:update` command.

```bash
npm run update
```

or you can run the update artisan command directly:

```bash
php artisan docs:update
```

Update here
