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

namespace D2DSoft\DataMigration\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action as AppAction;
use Magento\Framework\View\Result\PageFactory;
use D2DSoft\DataMigration\Helper\Data as HelperData;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Backend\Model\Session;

abstract class Migration extends AppAction
{
    protected $resultPageFactory;

    protected $helper;

    protected $migrationApp;

    protected $authSession;

    protected $session;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        HelperData $helper,
        AuthSession $authSession,
        Session $session
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->authSession = $authSession;
        $this->session = $session;
    }

    protected function getMigrationApp()
    {
        if($this->migrationApp){
            return $this->migrationApp;
        }
        $user = $this->authSession->getUser();
        $library_folder = $this->helper->getLibraryFolder();
        include_once $this->helper->getInitLibrary();
        \D2dInit::initEnv();
        $app = \D2dInit::getAppInstance(\D2dInit::APP_HTTP, \D2dInit::TARGET_RAW, 'magento20');
        $app->setRequest($this->getRequest()->getParams());
        $config = array();
        $config['user_id'] = $user->getId();
        $config['upload_dir'] = $library_folder . '/files';
        $config['upload_location'] = ltrim($this->helper->getLibraryLocation()) . '/files';
        $config['log_dir'] = $library_folder . '/log';
        $app->setConfig($config);
        $this->migrationApp = $app;
        return $this->migrationApp;
    }

    public function setMessage($type, $message){
        $messages = $this->session->getMigrationMessage();
        if(!$messages)
            $messages = array();
        $messages[] = array(
            'type' => $type,
            'message' => $message
        );
        $this->session->setMigrationMessage($messages);
        return $this;
    }

    public function getMessage(){
        $messages = $this->session->getMigrationMessage();
        $this->session->setMigrationMessage(array());
        return $messages;
    }
}