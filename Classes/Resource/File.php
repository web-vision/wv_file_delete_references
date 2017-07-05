<?php
namespace WebVision\WvFileDeleteReferences\Resource;

/*
 * This file is part of the wv_file_delete_references Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Core\Resource\File as CoreFile;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use WebVision\WvFileDeleteReferences\Domain\Repository\FileReferenceRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * File representation in the file abstraction layer.
 * Extended to provide an instance of FileReferenceRepository.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class File extends CoreFile
{
    /**
     * @var FileReferenceRepository
     */
    protected $fileReferenceRepository = null;

    /**
     * Constructor for a file object.
     * Extended to get files references.
     *
     * @param array $fileData
     * @param ResourceStorage $storage
     * @param array $metaData
     *
     * @return void
     */
    public function __construct(array $fileData, ResourceStorage $storage, array $metaData = [])
    {
        parent::__construct($fileData, $storage, $metaData);

        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $queryFactory = GeneralUtility::makeInstance(QueryFactory::class);
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $queryFactory->injectObjectManager($objectManager);
        $persistenceManager->injectQueryFactory($queryFactory);

        $this->fileReferenceRepository = GeneralUtility::makeInstance(
            FileReferenceRepository::class,
            $objectManager,
            $persistenceManager
        );
    }

    /**
     * @return FileReferenceRepository
     */
    public function getFileReferenceRepository() {
        return $this->fileReferenceRepository;
    }
}
