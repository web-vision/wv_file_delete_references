<?php

/*
 * This file is part of the wv_file_delete_references Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Copyright (c) 2017 web-vision GmbH
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'web-vision: Delete files with references',
    'description' => 'Adds a button to files in filelist to delete the file and all corresponding references.',
    'category' => 'backend',
    'version' => '0.0.1',
    'state' => 'alpha',
    'clearcacheonload' => true,
    'author' => 'Yannick Hermes',
    'author_email' => 'y.hermes@web-vision.de',
    'author_company' => 'web-vision GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-7.99.99',
        ],
    ],
];
