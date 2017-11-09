<?php

/*
 * This file is part of the wv_file_delete_references Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * Copyright (c) 2017 web-vision GmbH
 */

use WebVision\WvFileDeleteReferences\Controller;

/**
 * Override commit route for files.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
return [
    /**
     * Gateway for TCE (TYPO3 Core Engine) file-handling through POST forms.
     * Override cores gateway to pass an additional parameter.
     *
     * For syntax and API information, see the document 'TYPO3 Core APIs'
     */
    'tce_file' => [
        'path' => '/file/commit',
        'target' => Controller\File\FileController::class . '::mainAction'
    ]
];
