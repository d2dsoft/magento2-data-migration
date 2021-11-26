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

namespace D2DSoft\DataMigration\Controller\Adminhtml\Migration;

use D2DSoft\DataMigration\Controller\Adminhtml\Migration;

class Index extends Migration
{
    public function execute()
    {
        if(!$this->helper->isInstallLibrary()){
            $this->_redirect('*/*/license');
            return;
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('D2DSoft_DataMigration::datamigration');
        $resultPage->getConfig()->getTitle()->prepend(__('Data Migration'));
        $block = $resultPage->getLayout()->getBlock('datamigration.migration.index');
        $app = $this->getMigrationApp();
        $target = $app->getInitTarget();
        $response = $app->process(\D2dInit::PROCESS_INIT);
        $html = '';
        if($response['status'] == \D2dCoreLibConfig::STATUS_SUCCESS){
            $html = $response['html'];
        }
        $block->setHtmlContent($html)
            ->setMigrationMessage($this->getMessage())
            ->setJsConfig($target->getConfigJs());
        return $resultPage;
    }
}