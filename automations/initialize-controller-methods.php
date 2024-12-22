<?php

// Directory to scan
// $directory = __DIR__ . '/app/Http/Controllers/Admin'; // Change to your controllers directory
$directory ='../app/Http/Controllers/WEB/Admin'; // Change to your controllers directory

// Functions to check and initialize
$requiredFunctions = [
    'create',
    'show',
    'store',
    'update',
    'edit',
    'destroy'
];

// Check if the directory exists
if (!is_dir($directory)) {
    die("Error: Directory '$directory' does not exist.\n");
}

// Get all PHP files in the directory
$files = glob($directory . '/*.php');

foreach ($files as $file) {
    echo "Processing file: $file\n";

    // Read the file contents
    $contents = file_get_contents($file);

    // Check for the presence of each required function
    $missingFunctions = [];
    foreach ($requiredFunctions as $function) {
        if (!preg_match("/public function $function\s*\(/", $contents)) {
            $missingFunctions[] = $function;
        }
    }

    // If all functions are present, skip the file
    if (empty($missingFunctions)) {
        echo "All required functions are present.\n";
        continue;
    }

    // Add missing functions
    $newMethods = "";
    foreach ($missingFunctions as $function) {
        $newMethods .= <<<PHP

    /**
     * Handle the $function action.
     */
    public function $function()
    {
        // TODO: Implement $function logic.
    }

PHP;
    }

    // Insert the new methods before the closing PHP class bracket
    $contents = preg_replace('/}\s*$/', $newMethods . "\n}", $contents);

    // Write the updated contents back to the file
    file_put_contents($file, $contents);

    echo "Added missing functions: " . implode(', ', $missingFunctions) . "\n";
}

echo "Processing complete.\n";
