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
use D2DSoft\DataMigration\Helper\Data as HelperData;

class Setup extends Migration
{
    public function execute()
    {
        $license = $this->getRequest()->getParam('license');
        $install = $this->downloadAndExtraLibrary($license);
        if(!$install){
            return $this->_redirect('*/*/license');
        }
        $app = $this->getMigrationApp();
        $initTarget = $app->getInitTarget();
        $install_db = $initTarget->setupDatabase($license);
        if(!$install_db){
            return $this->_redirect('*/*/license');
        }
        return $this->_redirect('*/*/index');
    }

    protected function downloadAndExtraLibrary($license = '')
    {
        $url = HelperData::PACKAGE_URL;
        $library_folder = $this->helper->getLibraryFolder();
        if(!is_dir($library_folder))
            @mkdir($library_folder, 0777, true);
        $tmp_path = $library_folder . '/resources.zip';
        $data = array(
            'license' => $license
        );
        if(class_exists('\Zend_Http_Client')){
            $client = new \Zend_Http_Client();
            $method = \Zend_Http_Client::POST;
        } else {
            $client = new \Laminas\Http\Client();
            $method = \Laminas\Http\Request::METHOD_POST;
        }
        if(class_exists('\Zend_Json_Encoder')){
            $json_data = \Zend_Json_Encoder::encode($data);
        } else {
            $json_data = \Laminas\Json\Json::encode($data);
        }
        $client->setUri($url)
            ->setStream($tmp_path)
            ->setMethod($method)
            ->setRawData($json_data);
        try {
            $client->request()->getBody();
            if(class_exists('\Zend_Filter_Compress_Zip')){
                $zip = new \Zend_Filter_Compress_Zip();
            } else {
                $zip = new \Laminas\Filter\Compress\Zip();
            }
            $zip->setTarget($library_folder);
            $zip->decompress($tmp_path);
            @unlink($tmp_path);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}