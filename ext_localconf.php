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

call_user_func(
    function ($extKey) {
        $typo3ConfigurationVariables = 'TYPO3_CONF_VARS';

        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS,
            [
                $typo3ConfigurationVariables => [
                    'SYS' => [
                        'Objects' => [
                            'TYPO3\\CMS\\Filelist\\FileList' => [
                                'className' => \WebVision\WvFileDeleteReferences\Backend\FileList::class
                            ],
                            'TYPO3\\CMS\\Core\\Utility\\File\\ExtendedFileUtility' => [
                                'className' => \WebVision\WvFileDeleteReferences\Utility\File\ExtendedFileUtility::class
                            ],
                            'TYPO3\\CMS\\Core\\Resource\\File' => [
                                'className' => \WebVision\WvFileDeleteReferences\Resource\File::class
                            ],
                        ],
                    ],
                ],
            ]
        );
    },
    $_EXTKEY
);
