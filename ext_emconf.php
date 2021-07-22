 
<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Hebo Feuser Export',
    'description' => 'This TYPO3 extension for adding a command that exports all typo3 users',
    'category' => 'plugin',
    'author' => 'Victor Willhuber',
    'author_company' => 'Hebotek',
    'author_email' => 'victorwillhuber@gmail.com',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4',
            'femanager' => '6.3.0'
        ]
    ],
    'autoload' => [
        'psr-4' => [
            'Hebotek\\HeboFeuserExport\\' => 'Classes'
        ]
    ],
];