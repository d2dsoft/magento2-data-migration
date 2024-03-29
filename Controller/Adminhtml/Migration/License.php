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
 * @author     D2dSoft Developers <developer@d2d-soft.com>
 * @copyright  Copyright (c) 2021 D2dSoft (https://d2d-soft.com)
 * @license    https://d2d-soft.com/license/AFL.txt
 */

namespace D2DSoft\DataMigration\Controller\Adminhtml\Migration;

use D2DSoft\DataMigration\Controller\Adminhtml\Migration;

class License extends Migration
{
    public function execute()
    {
        if(!ini_get('allow_url_fopen')){
            $this->setMessage('error', 'The PHP "allow_url_fopen" must is enabled. Please follow <a href="https://www.a2hosting.com/kb/developer-corner/php/using-php.ini-directives/php-allow-url-fopen-directive" target="_blank">here</a> to enable the setting.');
        }
        if (!extension_loaded('zip')) {
            $this->setMessage('error', 'PHP Zip extension is not installed. Please install the Zip extension.');
        }
        /*if (!function_exists('eval')) {
            $this->setMessage('error', 'Please enable the eval function.');
        }*/
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('D2DSoft_DataMigration::datamigration');
        $resultPage->getConfig()->getTitle()->prepend(__('Data Migration'));
        $block = $resultPage->getLayout()->getBlock('datamigration.migration.license');
        $block->setMigrationMessage($this->getMessage());
        return $resultPage;
    }
}