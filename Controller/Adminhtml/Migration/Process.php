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
use Zend_Json_Encoder;

class Process extends Migration
{
    public function execute()
    {
        $type = $this->getRequest()->getParam('action_type', 'import');
        return $this->$type();
    }

    public function import(){
        $app = $this->getMigrationApp();
        $process = $this->getRequest()->getParam('process');
        if(!$process || !in_array($process, array(
                \D2dInit::PROCESS_SETUP,
                \D2dInit::PROCESS_CHANGE,
                \D2dInit::PROCESS_UPLOAD,
                \D2dInit::PROCESS_STORED,
                \D2dInit::PROCESS_STORAGE,
                \D2dInit::PROCESS_CONFIG,
                \D2dInit::PROCESS_CONFIRM,
                \D2dInit::PROCESS_PREPARE,
                \D2dInit::PROCESS_CLEAR,
                \D2dInit::PROCESS_IMPORT,
                \D2dInit::PROCESS_RESUME,
                \D2dInit::PROCESS_REFRESH,
                \D2dInit::PROCESS_FINISH))){
            $this->responseJson(array(
                'status' => 'error',
                'message' => 'Process Invalid.'
            ));
            return;
        }
        $response = $app->process($process);
        $this->responseJson($response);
        return;
    }

    public function download(){
        $app = $this->getMigrationApp();
        $app->process(\D2dInit::PROCESS_DOWNLOAD);
    }

    protected function responseJson($data)
    {
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-type', 'application/json' ,true);
        $this->getResponse()
            ->setBody(Zend_Json_Encoder::encode($data));
        return;
    }
}