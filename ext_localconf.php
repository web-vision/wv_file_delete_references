<?php

/*
 * This file is part of the wv_file_delete_references Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * Copyright (c) 2017 web-vision GmbH
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
