<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Cdnnow\Core\Handlers\HandlerRegister;

Loc::loadMessages(__FILE__);

class Cdnnow_Core extends CModule
{
    var $MODULE_ID = "cdnnow.core";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $errors;

    function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME        = Loc::getMessage("CDNNOW_MODULE_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("CDNNOW_MODULE_INSTALL_DESCRIPTION");
        $this->PARTNER_NAME       = Loc::getMessage("CDNNOW_MODULE_PARTNER_NAME");
        $this->PARTNER_URI        = Loc::getMessage("CDNNOW_MODULE_PARTNER_URI");
    }

    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        ModuleManager::RegisterModule("cdnnow.core");
        return true;
    }

    function InstallDB()
    {
        return true;
    }

    function InstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'main',
            'OnPageStart',
            'cdnnow.core',
            HandlerRegister::class,
            'init'
        );

        return true;
    }

    function InstallFiles()
    {
        return true;
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        ModuleManager::UnRegisterModule("cdnnow.core");
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

    function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'main',
            'OnPageStart',
            'cdnnow.core',
            HandlerRegister::class,
            'init'
        );

        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }
}
