<?php
namespace WebVision\WvFileDeleteReferences\Controller\File;

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

use TYPO3\CMS\Backend\Controller\File\FileController as CoreFileController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Override cores FileController Class for rendering File>Filelist to pass
 * 'deleteReferences' parameter from routing.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class FileController extends CoreFileController
{
    /**
     * Overrides parent::init() method to pass 'deleteReferences' parameter.
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $deleteReferences = GeneralUtility::_GET('deleteReferences');

        if (! empty($deleteReferences)) {
            $this->file['delete'][0]['deleteReferences'] = $deleteReferences;
        }
    }
}
