<?php
namespace WebVision\WvFileDeleteReferences\Backend;

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

use TYPO3\CMS\Filelist\FileList as CoreFileList;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Type\Bitmask\JsConfirmation;

/**
 * Extends Class for rendering of File > Filelist.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class FileList extends CoreFileList
{
    /**
     * Extends the edit control section with a function for deleting a file
     * with all of its references.
     *
     * @param File|Folder $fileOrFolderObject Array with information about the file/directory for which to make the edit control section for the listing.
     * @return string HTML-table
     */
    public function makeEdit($fileOrFolderObject)
    {
        $languagePathExt = 'LLL:EXT:wv_file_delete_references/Resources/Private/Language/locallang_be.xlf:';

        $controlSection = $this->removeLastOccurence(
            '</div>',
            '',
            parent::makeEdit($fileOrFolderObject)
        );

        // delete the file
        if ($fileOrFolderObject->checkActionPermission('delete') && ! $fileOrFolderObject instanceof Folder) {
            $identifier = $fileOrFolderObject->getIdentifier();

            $referenceCountText = BackendUtility::referenceCount(
                'sys_file',
                $fileOrFolderObject->getUid(),
                ' ' . $this->getLanguageService()->sL(
                    'LLL:EXT:lang/locallang_core.xlf:labels.referencesToFile'
                )
            );

            $confirmationCheck = '0';

            if ($this->getBackendUser()->jsConfirmation(JsConfirmation::DELETE)) {
                $confirmationCheck = '1';
            }

            $deleteUrl = BackendUtility::getModuleUrl(
                'tce_file',
                [
                    'deleteReferences' => true,
                ]
            );

            $confirmationMessage = sprintf(
                $this->getLanguageService()->sL(
                    $languagePathExt . 'mess.deleteReferences'
                ),
                $fileOrFolderObject->getName()
            ) . $referenceCountText;

            $title = $this->getLanguageService()->sL(
                $languagePathExt . 'cm.deleteReferences'
            );

            $cells['deleteReferences'] =
                '<a href="#" class="btn btn-default t3js-filelist-delete" data-content="'
                    . htmlspecialchars($confirmationMessage)
                    . '" data-check="' . $confirmationCheck
                    . '" data-delete-url="' . htmlspecialchars($deleteUrl)
                    . '" data-title="' . htmlspecialchars($title)
                    . '" data-identifier="' . htmlspecialchars($fileOrFolderObject->getCombinedIdentifier())
                    . '" data-veri-code="' . $this->getBackendUser()->veriCode()
                    . '" title="' . htmlspecialchars($title) . '">'
                    . $this->iconFactory->getIcon(
                        'actions-edit-delete-references', Icon::SIZE_SMALL
                    )->render() . '</a>';
        }

        return $controlSection . (! empty($cells) ? implode('', $cells) : '') . '</div>';
    }

    /**
     * Removes last occurence of string in string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     *
     * @return string
     */
    protected function removeLastOccurence($search, $replace, $subject) {
        return strrev(
            implode(
                strrev($replace),
                explode(
                    strrev($search),
                    strrev($subject),
                    2
                )
            )
        );
    }
}
