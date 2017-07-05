<?php
namespace WebVision\WvFileDeleteReferences\Domain\Repository;

/*
 * This file is part of the wv_file_delete_references Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * Repository for "sys_file_reference" objects.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class FileReferenceRepository extends Repository
{
    /**
     * Constructs a new Repository
     *
     * @param ObjectManagerInterface $objectManager
     * @param PersistenceManagerInterface $persistenceManager
     * @param QueryFactoryInterface $queryFactory
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        PersistenceManagerInterface $persistenceManager = null
    ) {
        parent::__construct($objectManager);
        $this->injectPersistenceManager($persistenceManager);
        return $this;
    }

    /**
     * Override default query settings.
     *
     * @return void
     */
    public function initializeObject() {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface */
        $defaultQuerySettings = $this->objectManager->get(
            'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\QuerySettingsInterface'
        );

        $defaultQuerySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * Finds objects matching the given uid_local.
     *
     * @param int $uid_local
     * @return QueryResult|null
     * @api
     */
    public function findByUidLocal($uid_local)
    {
        $query = $this->createQuery();
        $query->matching($query->equals('uid_local', $uid_local));
        return $query->execute();
    }
}
