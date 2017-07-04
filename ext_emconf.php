<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
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
