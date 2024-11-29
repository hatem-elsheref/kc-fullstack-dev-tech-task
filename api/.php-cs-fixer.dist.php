<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__) // This tells PHP-CS-Fixer to look in the current directory and subdirectories
    ->exclude('vendor') // Exclude vendor directory
    ->name('*.php'); // Look for PHP files

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true, // Apply PSR-12 rules
])
->setFinder($finder);
