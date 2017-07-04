<?php
namespace WebVision\WvFileDeleteReferences\Utility\File;

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

use TYPO3\CMS\Core\Utility\File\ExtendedFileUtility as CoreExtendedFileUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Override ExtendedFileUtility to overload delete function to add a condition
 * for deleting file with all of its references.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class ExtendedFileUtility extends CoreExtendedFileUtility
{
    /**
     * Deleting files and folders (action=4)
     *
     * This code is almost the same as the parents one, the only difference
     * is the condition for deleting references
     * ($cmds['deleteReferences'] == 1).
     *
     * @param array $cmds $cmds['data'] is the file/folder to delete
     * @return bool Returns TRUE upon success
     */
    public function func_delete(array $cmds)
    {
        $languagePathExt = 'LLL:EXT:wv_file_delete_references/Resources/Private/Language/locallang_be.xlf:';

        $result = false;
        if (! $this->isInit) {
            return $result;
        }
        // Example indentifier for $cmds['data'] => "4:mypath/tomyfolder/myfile.jpg"
        // for backwards compatibility: the combined file identifier was the path+filename
        try {
            $fileObject = $this->getFileObject($cmds['data']);
        } catch (ResourceDoesNotExistException $e) {
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                sprintf(
                    $this->getLanguageService()->sL(
                        'LLL:EXT:lang/locallang_core.xlf:message.description.fileNotFound'
                    ),
                    $cmds['data']
                ),
                $this->getLanguageService()->sL(
                    'LLL:EXT:lang/locallang_core.xlf:message.header.fileNotFound'
                ),
                FlashMessage::ERROR,
                true
            );
            $this->addFlashMessage($flashMessage);

            return false;
        }

        // checks to delete the file
        if ($fileObject instanceof File) {
            // check if the file still has references
            // Exclude sys_file_metadata records as these are no use references
            $databaseConnection = $this->getDatabaseConnection();
            $table = 'sys_refindex';
            $refIndexRecords = $databaseConnection->exec_SELECTgetRows(
                '*',
                $table,
                'deleted=0 AND ref_table=' . $databaseConnection->fullQuoteStr('sys_file', $table)
                . ' AND ref_uid=' . (int)$fileObject->getUid()
                . ' AND tablename != ' . $databaseConnection->fullQuoteStr('sys_file_metadata', $table)
            );

            $deleteFile = true;

            if ($cmds['deleteReferences'] != 1) {
                if (!empty($refIndexRecords)) {
                    $shortcutContent = [];
                    $brokenReferences = [];

                    foreach ($refIndexRecords as $fileReferenceRow) {
                        if ($fileReferenceRow['tablename'] === 'sys_file_reference') {
                            $row = $this->transformFileReferenceToRecordReference($fileReferenceRow);
                            $shortcutRecord = BackendUtility::getRecord($row['tablename'], $row['recuid']);

                            if ($shortcutRecord) {
                                $shortcutContent[] = '[record:' . $row['tablename'] . ':' . $row['recuid'] . ']';
                            } else {
                                $brokenReferences[] = $fileReferenceRow['ref_uid'];
                            }
                        }
                    }
                    if (! empty($brokenReferences)) {
                        // render a message that the file has broken references
                        $flashMessage = GeneralUtility::makeInstance(
                            FlashMessage::class,
                            sprintf(
                                $this->getLanguageService()->sL(
                                    'LLL:EXT:lang/locallang_core.xlf:message.description.fileHasBrokenReferences'
                                ),
                                count($brokenReferences)
                            ),
                            $this->getLanguageService()->sL(
                                'LLL:EXT:lang/locallang_core.xlf:message.header.fileHasBrokenReferences'
                            ),
                            FlashMessage::INFO,
                            true
                        );
                        $this->addFlashMessage($flashMessage);
                    }
                    if (! empty($shortcutContent)) {
                        // render a message that the file could not be deleted
                        $flashMessage = GeneralUtility::makeInstance(
                            FlashMessage::class,
                            sprintf(
                                $this->getLanguageService()->sL(
                                    'LLL:EXT:lang/locallang_core.xlf:message.description.fileNotDeletedHasReferences'
                                ),
                                $fileObject->getName()
                            ) . ' ' . implode(', ', $shortcutContent),
                            $this->getLanguageService()->sL(
                                'LLL:EXT:lang/locallang_core.xlf:message.header.fileNotDeletedHasReferences'
                            ),
                            FlashMessage::WARNING,
                            true
                        );
                        $this->addFlashMessage($flashMessage);
                        $deleteFile = false;
                    }
                }
            }

            $result = null;

            if ($deleteFile) {
                try {
                    if ($cmds['deleteReferences'] == 1) {
                        $preProcKey = 'deletion_PreProc';
                        $preDeletionHooks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']
                            ['ext/wv_file_delete_references/Utility/ExtendedFileUtility.php'][$preProcKey];

                        if (! empty($preDeletionHooks) && is_array($preDeletionHooks)) {
                            foreach ($preDeletionHooks as $hook) {
                                $this->execHook($hook, $preProcKey, $fileObject, false);
                            }
                        }

                        $result = $fileObject->delete();

                        if ($result) {
                            $this->deleteReferences($fileObject);
                        }

                        $postProcKey = 'deletion_PostProc';
                        $postDeletionHooks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']
                            ['ext/wv_file_delete_references/Utility/ExtendedFileUtility.php'][$postProcKey];

                        if (! empty($postDeletionHooks) && is_array($postDeletionHooks)) {
                            foreach ($postDeletionHooks as $hook) {
                                $this->execHook($hook, $postProcKey, $fileObject, $result);
                            }
                        }

                        // show the user that the file was deleted
                        $flashMessage = GeneralUtility::makeInstance(
                            FlashMessage::class,
                            sprintf($this->getLanguageService()->sL(
                                $languagePathExt . 'message.description.fileDeleted'
                            ),
                            $fileObject->getName()),
                            $this->getLanguageService()->sL($languagePathExt . 'message.header.fileDeleted'),
                            FlashMessage::OK,
                            true
                        );
                        // Log success
                        $this->writeLog(
                            4, 0, 1,
                            'File "%s" and all of it\'s references deleted',
                            [$fileObject->getIdentifier()]
                        );
                    } else {
                        $result = $fileObject->delete();
                        // show the user that the file was deleted
                        $flashMessage = GeneralUtility::makeInstance(
                            FlashMessage::class,
                            sprintf($this->getLanguageService()->sL(
                                'LLL:EXT:lang/locallang_core.xlf:message.description.fileDeleted'
                            ), $fileObject->getName()),
                            $this->getLanguageService()->sL(
                                'LLL:EXT:lang/locallang_core.xlf:message.header.fileDeleted'
                            ),
                            FlashMessage::OK,
                            true
                        );
                        // Log success
                        $this->writeLog(4, 0, 1, 'File "%s" deleted', [$fileObject->getIdentifier()]);
                    }

                    $this->addFlashMessage($flashMessage);
                } catch (\TYPO3\CMS\Core\Resource\Exception\InsufficientFileAccessPermissionsException $e) {
                    $this->writeLog(
                        4, 1, 112,
                        'You are not allowed to access the file', [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.YouAreNotAllowedToAccessTheFile',
                        [$fileObject->getIdentifier()]
                    );
                } catch (NotInMountPointException $e) {
                    $this->writeLog(
                        4, 1, 111,
                        'Target was not within your mountpoints! T="%s"',
                        [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.TargetWasNotWithinYourMountpoints',
                        [$fileObject->getIdentifier()]
                    );
                } catch (\RuntimeException $e) {
                    $this->writeLog(
                        4, 1, 110,
                        'Could not delete file "%s". Write-permission problem?',
                        [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.CouldNotDeleteFile',
                        [$fileObject->getIdentifier()]
                    );
                }
            }
        } else {
            /** @var Folder $fileObject */
            if (!$this->folderHasFilesInUse($fileObject)) {
                try {
                    $result = $fileObject->delete(true);
                    if ($result) {
                        // notify the user that the folder was deleted
                        /** @var FlashMessage $flashMessage */
                        $flashMessage = GeneralUtility::makeInstance(
                            FlashMessage::class,
                            sprintf(
                                $this->getLanguageService()->sL(
                                    'LLL:EXT:lang/locallang_core.xlf:message.description.folderDeleted'
                                ), $fileObject->getName()
                            ),
                            $this->getLanguageService()->sL(
                                'LLL:EXT:lang/locallang_core.xlf:message.header.folderDeleted'
                            ),
                            FlashMessage::OK,
                            true
                        );
                        $this->addFlashMessage($flashMessage);
                        // Log success
                        $this->writeLog(
                            4, 0, 3,
                            'Directory "%s" deleted',
                            [$fileObject->getIdentifier()]
                        );
                    }
                } catch (InsufficientUserPermissionsException $e) {
                    $this->writeLog(
                        4, 1, 120,
                        'Could not delete directory! Is directory "%s" empty? ' .
                            '(You are not allowed to delete directories recursively).',
                        [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.CouldNotDeleteDirectory',
                        [$fileObject->getIdentifier()]
                    );
                } catch (InsufficientFolderAccessPermissionsException $e) {
                    $this->writeLog(
                        4, 1, 123,
                        'You are not allowed to access the directory',
                        [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.YouAreNotAllowedToAccessTheDirectory',
                        [$fileObject->getIdentifier()]
                    );
                } catch (NotInMountPointException $e) {
                    $this->writeLog(
                        4, 1, 121,
                        'Target was not within your mountpoints! T="%s"',
                        [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.TargetWasNotWithinYourMountpoints',
                        [$fileObject->getIdentifier()]
                    );
                } catch (\TYPO3\CMS\Core\Resource\Exception\FileOperationErrorException $e) {
                    $this->writeLog(
                        4, 1, 120,
                        'Could not delete directory "%s"! Write-permission problem?',
                        [$fileObject->getIdentifier()]
                    );
                    $this->addMessageToFlashMessageQueue(
                        'FileUtility.CouldNotDeleteDirectory',
                        [$fileObject->getIdentifier()]
                    );
                }
            }
        }

        return $result;
    }

    /**
     * @param string $method
     * @param File $fileObject
     * @param bool $result
     *
     * @return void
     */
    private function execHook($className, $method, File $fileObject, $result = null) {
        $instance = GeneralUtility::makeInstance($className);
        call_user_func_array([$instance, $method], [$fileObject, $result]);
    }

    /**
     * @param File $fileObject
     *
     * @return void
     */
    private function deleteReferences(File $fileObject) {
        $fileReferenceRepository = $fileObject->getFileReferenceRepository();

        foreach(
            $fileReferenceRepository->findByUidLocal(
                $fileObject->getProperty('uid')
            ) as $fileReference
        ) {
            $fileReferenceRepository->update($fileReference->delete()->hide());
        }
    }
}
