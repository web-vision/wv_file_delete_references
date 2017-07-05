<?php

/*
 * This file is part of the wv_file_delete_references Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Copyright (c) 2017 web-vision GmbH
 */

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
            'actions-edit-delete-references',
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'EXT:' . $extKey .
                    '/Resources/Public/Icons/Backend/FileList/actions-edit-delete-references.svg',
            ]
        );
    },
    $_EXTKEY
);
