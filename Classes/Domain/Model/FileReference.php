<?php
namespace WebVision\WvFileDeleteReferences\Domain\Model;

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

use TYPO3\CMS\Extbase\Domain\Model\FileReference as CoreFileReference;

/**
 * Model for a "sys_file_reference" data set.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class FileReference extends CoreFileReference
{
    /**
     * @var int
     */
    protected $uidForeign;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $tablenames;

    /**
     * @var int
     */
    protected $sysLanguageUid;

    /**
     * @var int
     */
    protected $deleted;

    /**
     * @var int
     */
    protected $hidden;

    /**
     * @return int
     */
    public function getUidForeign() {
        return $this->uidForeign;
    }

    /**
     * @param $uidForeign int
     *
     * @return FileReference
     */
    public function setUidForeign($uidForeign) {
        $this->uidForeign = $uidForeign;
        return $this;
    }

    /**
     * @return string
     */
    public function getTablenames() {
        return $this->tablenames;
    }

    /**
     * @param $tablenames string
     *
     * @return FileReference
     */
    public function setTablenames($tablenames) {
        $this->tablenames = $tablenames;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param $title string
     *
     * @return FileReference
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getSysLanguageUid() {
        return $this->sysLanguageUid;
    }

    /**
     * @param $sysLanguageUid int
     *
     * @return FileReference
     */
    public function setSysLanguageUid($sysLanguageUid) {
        $this->sysLanguageUid = $sysLanguageUid;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeleted() {
        return $this->deleted;
    }

    /**
     * @param $deleted int
     *
     * @return FileReference
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return FileReference
     */
    public function delete() {
        $this->setDeleted(1);

        return $this;
    }

    /**
     * @return int
     */
    public function getHidden() {
        return $this->hidden;
    }

    /**
     * @param $hidden int
     *
     * @return FileReference
     */
    public function setHidden($hidden) {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return FileReference
     */
    public function hide() {
        $this->setHidden(1);

        return $this;
    }
}
