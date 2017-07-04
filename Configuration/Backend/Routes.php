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

use WebVision\WvFileDeleteReferences\Controller;

/**
 * Definitions for routes provided by EXT:backend
 * Contains all "regular" routes for entry points
 *
 * Please note that this setup is preliminary until all core use-cases are set up here.
 * Especially some more properties regarding modules will be added until TYPO3 CMS 7 LTS, and might change.
 *
 * Currently the "access" property is only used so no token creation + validation is made,
 * but will be extended further.
 */

/**
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
