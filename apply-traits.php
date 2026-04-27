<?php

$resourcesDir = __DIR__ . '/app/Filament/Resources';

foreach (glob($resourcesDir . '/*.php') as $file) {
    $name = basename($file);
    
    if (strpos($name, 'Cent') === 0) {
        echo "SKIP CENT: $name\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    if (strpos($content, 'HasPerfilInternoAccess') !== false) {
        echo "YA TIENE: $name\n";
        continue;
    }
    
    // Add use statements after the last "use " line before "class"
    $lastUsePos = strrpos($content, 'use ');
    $classPos = strpos($content, 'class ', $lastUsePos);
    
    if ($lastUsePos !== false && $classPos !== false && $lastUsePos < $classPos) {
        // Find end of that line
        $lineEnd = strpos($content, "\n", $lastUsePos);
        if ($lineEnd !== false) {
            $insertAfter = $lineEnd + 1;
            $content = substr_replace($content, "use App\\Filament\\Concerns\\AdminPanelOnly;\nuse App\\Filament\\Concerns\\HasPerfilInternoAccess;\n", $insertAfter, 0);
        }
    } else {
        // Insert before class declaration
        $content = str_replace(
            "class ",
            "use App\\Filament\\Concerns\\AdminPanelOnly;\nuse App\\Filament\\Concerns\\HasPerfilInternoAccess;\n\nclass ",
            $content
        );
    }
    
    // Add traits inside class
    $content = str_replace(
        'class ',
        "class ",
        $content
    );
    
    // Find class declaration and add traits after opening brace
    if (strpos($content, 'use AdminPanelOnly') === false) {
        $content = preg_replace(
            '/(class \w+ extends Resource\s*\{)/',
            "$1\n    use AdminPanelOnly, HasPerfilInternoAccess;\n",
            $content
        );
    } else {
        $content = str_replace(
            'use AdminPanelOnly;',
            'use AdminPanelOnly, HasPerfilInternoAccess;',
            $content
        );
    }
    
    file_put_contents($file, $content);
    echo "ACTUALIZADO: $name\n";
}

echo "\nDone.\n";
