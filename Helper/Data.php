<?php

/**
 * D2dSoft
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL v3.0) that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL: https://d2d-soft.com/license/AFL.txt
 *
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this extension/plugin/module to newer version in the future.
 *
 * @author     D2dSoft Developers <develop@d2d-soft.com>
 * @copyright  Copyright (c) 2021 D2dSoft (https://d2d-soft.com)
 * @license    https://d2d-soft.com/license/AFL.txt
 */

namespace D2DSoft\DataMigration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    const PACKAGE_URL = 'https://d2d-soft.com/download_package.php';

    protected $directoryList;

    public function __construct(
        Context $context,
        DirectoryList $directoryList
    ){
        parent::__construct($context);
        $this->directoryList = $directoryList;
    }

    public function getLibraryLocation(){
        return '/d2dsoft/datamigration';
    }

    public function getLibraryFolder(){
        $location = $this->getLibraryLocation();
        $folder = $this->directoryList->getPath('media') . $location;
        return $folder;
    }

    public function getInitLibrary(){
        $library_folder = $this->getLibraryFolder();
        return $library_folder . '/resources/init.php';
    }

    public function isInstallLibrary(){
        $init_file = $this->getInitLibrary();
        return file_exists($init_file);
    }
}