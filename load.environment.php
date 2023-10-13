<?php

/**
 * @file
 * This file is included very early. See autoload files in composer.json.
 */

use Dotenv\Dotenv;

/**
 * Load any .env file. See /.env.example.
 */
// Load using createMutable to ensure .env file is parsed again. Otherwise
// Docker Composer environment variables could integer quotes or double quotes
// where we want strings.
$dotenv = Dotenv::createMutable(__DIR__);
$dotenv->safeLoad();
