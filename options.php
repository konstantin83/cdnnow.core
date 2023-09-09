<?php

use Cdnnow\Core\Options;

$module_id = Options::moduleId;

global $USER;
$RIGHT_R = $RIGHT_W = $USER->IsAdmin();

if (!$RIGHT_W && !$RIGHT_R) {
    return;
}

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/main/options.php');
IncludeModuleLangFile(__FILE__);

$mainOptions = [
    [
        'module_cdnnow_active',
        GetMessage('CDNNOW_OPTIONS_ACTIVE'),
        ['checkbox'],
        'hint' => 'Включение CDN кэширования в CDNnow',
    ],
    [
        'module_cdnnow_address',
        GetMessage('CDNNOW_OPTIONS_ADDRESS'),
        ['text', '70'],
        'hint' => "Адрес аккаунта в сервисе CDNnow вида \"userХХХХХХ.clients-cdnnow.ru\" или \"cdn.{$_SERVER['HTTP_HOST']}\". Не указывайте протокол (HTTP/HTTPS), только сам домен.",
    ],
];

$fileTypeOptions = [];
foreach (Options::fileTypes as $key => $value) {
    $fileTypeOptions[] = [
        'module_cdnnow_' . $key,
        GetMessage($value['lang']),
        ['checkbox'],
        'hint' => implode(', ', $value['types']),
    ];
}

$aTabs = [
    [
        'DIV'   => 'edit1',
        'TAB'   => GetMessage('MAIN_TAB_SET'),
        'ICON'  => 'biconnector_settings',
        'TITLE' => GetMessage('MAIN_TAB_TITLE_SET'),
    ],
];

$tabControl = new CAdminTabControl('tabControl', $aTabs);

CModule::IncludeModule($module_id);

//<editor-fold default-state='collapsed' desc="Обработка POST запроса">
if ($REQUEST_METHOD == 'POST' && $Update . $Apply . $RestoreDefaults <> '' && $RIGHT_W && check_bitrix_sessid()) {
    if ($RestoreDefaults <> '') {
        COption::RemoveOption($module_id);
    } else {
        foreach ($mainOptions as $option) {
            $name = $option[0];
            $val  = trim($_REQUEST[$name], " \t\n\r");
            if ($option[2][0] == 'checkbox' && $val != 'Y') {
                $val = 'N';
            }
            COption::SetOptionString($module_id, $name, $val, $option[1]);
        }
        foreach ($fileTypeOptions as $option) {
            $name = $option[0];
            $val  = trim($_REQUEST[$name], " \t\n\r");
            if ($option[2][0] == 'checkbox' && $val != 'Y') {
                $val = 'N';
            }
            COption::SetOptionString($module_id, $name, $val, $option[1]);
        }
    }

    if ($_REQUEST['back_url_settings'] <> '') {
        if (($Apply <> '') || ($RestoreDefaults <> '')) {
            LocalRedirect(
                $APPLICATION->GetCurPage() . '?mid=' . urlencode($module_id) . '&lang=' . urlencode(
                    LANGUAGE_ID
                ) . '&back_url_settings=' . urlencode(
                    $_REQUEST['back_url_settings']
                ) . '&' . $tabControl->ActiveTabParam()
            );
        } else {
            LocalRedirect($_REQUEST['back_url_settings']);
        }
    } else {
        LocalRedirect(
            $APPLICATION->GetCurPage() . '?mid=' . urlencode($module_id) . '&lang=' . urlencode(
                LANGUAGE_ID
            ) . '&' . $tabControl->ActiveTabParam()
        );
    }
}
//</editor-fold>

//<editor-fold desc="Основные настройки">
?>
<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($module_id) ?>&amp;lang=<?= LANGUAGE_ID ?>">

    <?php
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>

    <tr class="heading">
        <td colspan="2"><b><?= GetMessage('CDNNOW_OPTIONS_HEADER_MAIN') ?></b></td>
    </tr>

    <?php
    $i = 0;
    foreach ($mainOptions as $option) {
        $val  = COption::GetOptionString($module_id, $option[0]);
        $type = $option[2];
        ?>
        <tr>
            <td width="40%" nowrap <?= ($type[0] == 'textarea') ? 'class="adm-detail-valign-top"' : '' ?>>
                <?php
                # Подсказки
                if ($option['hint']) {
                    ?>
                    <span id="hint_help_<?= ++$hintId ?>"></span>
                    <script>
                      BX.hint_replace(
                          BX('hint_help_<?=$hintId?>'),
                          '<?= CUtil::JSEscape($option['hint'])?>'
                      );
                    </script>
                    <?php
                }
                ?>
                <label for="<?= htmlspecialcharsbx($option[0]) ?>"><?= $option[1] ?>:</label>
            <td width="60%">
                <?php
                if ($type[0] == 'checkbox') { ?>
                    <input type="checkbox" name="<?= htmlspecialcharsbx($option[0]) ?>"
                           id="<?= htmlspecialcharsbx($option[0]) ?>"
                           value="Y"<?= ($val == 'Y') ? ' checked' : '' ?>>
                    <?php
                } elseif ($type[0] == 'text') { ?>
                    <input type="text" size="<?= $type[1] ?>" maxlength="255"
                           value="<?= htmlspecialcharsbx($val) ?>" name="<?= htmlspecialcharsbx($option[0]) ?>"
                           id="<?= htmlspecialcharsbx($option[0]) ?>">
                    <?php
                } elseif ($type[0] == 'textarea') { ?>
                    <textarea rows="<?= $type[1] ?>" cols="<?= $type[2] ?>"
                              name="<?= htmlspecialcharsbx($option[0]) ?>"
                              id="<?= htmlspecialcharsbx($option[0]) ?>"><?= htmlspecialcharsbx(
                            $val
                        ) ?></textarea>
                    <?php
                } elseif ($type[0] == 'selectbox') { ?>
                    <select name="<?= htmlspecialcharsbx($option[0]) ?>">
                        <?php
                        foreach ($type[1] as $key => $value) { ?>
                            <option value="<?= $key ?>"<?= ($val == $key) ? ' selected' : '' ?>><?= htmlspecialcharsbx(
                                    $value
                                ) ?></option>
                            <?php
                        } ?>
                    </select>
                    <?php
                } ?>
            </td>
        </tr>
        <?php
    }

    \Bitrix\Main\UI\Extension::load('ui.buttons');
    ?>
    <tr>
        <td colspan="2">
            <div class='adm-info-message-wrap'>
                <div class='adm-info-message'>
                    <?= $mainOptions[1]['hint'] ?>
                    <div class="cdnnow_buttons_wrap">
                        <a href="https://cdnnow.ru/#order" class="ui-btn ui-btn-primary" target="_blank">Регистрация в
                            CDNnow</a>
                        <a href='https://selfcare.cdnnow.ru/' class='ui-btn ui-btn-primary-dark' target='_blank'>Перейти
                            в личный
                            кабинет</a>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <?php
    //</editor-fold>


    //<editor-fold desc="Выбор типов файлов">
    ?>
    <tr class="heading">
        <td colspan="2"><b><?= GetMessage('CDNNOW_OPTIONS_HEADER_TYPES') ?></b></td>
    </tr>

    <?php
    foreach ($fileTypeOptions as $option) {
        $val  = COption::GetOptionString($module_id, $option[0]);
        $type = $option[2];
        ?>
        <tr>
            <td width="40%" nowrap <?= ($type[0] == 'textarea') ? 'class="adm-detail-valign-top"' : '' ?>>
                <?php
                # Подсказки
                if ($option['hint']) {
                    ?>
                    <span id="hint_help_<?= ++$hintId ?>"></span>
                    <script>
                      BX.hint_replace(
                          BX('hint_help_<?=$hintId?>'),
                          '<?= CUtil::JSEscape($option['hint'])?>'
                      );
                    </script>
                    <?php
                }
                ?>
                <label for="<?= htmlspecialcharsbx($option[0]) ?>"><?= $option[1] ?> <span
                            class='required'><sup><?= ++$i ?></sup></span>:</label>
            <td width="60%">
                <?php
                if ($type[0] == 'checkbox') { ?>
                    <input type="checkbox" name="<?= htmlspecialcharsbx($option[0]) ?>"
                           id="<?= htmlspecialcharsbx($option[0]) ?>"
                           value="Y"<?= ($val == 'Y') ? ' checked' : '' ?>>
                    <?php
                } elseif ($type[0] == 'text') { ?>
                    <input type="text" size="<?= $type[1] ?>" maxlength="255"
                           value="<?= htmlspecialcharsbx($val) ?>" name="<?= htmlspecialcharsbx($option[0]) ?>"
                           id="<?= htmlspecialcharsbx($option[0]) ?>">
                    <?php
                } elseif ($type[0] == 'textarea') { ?>
                    <textarea rows="<?= $type[1] ?>" cols="<?= $type[2] ?>"
                              name="<?= htmlspecialcharsbx($option[0]) ?>"
                              id="<?= htmlspecialcharsbx($option[0]) ?>"><?= htmlspecialcharsbx(
                            $val
                        ) ?></textarea>
                    <?php
                } elseif ($type[0] == 'selectbox') { ?>
                    <select name="<?= htmlspecialcharsbx($option[0]) ?>">
                        <?php
                        foreach ($type[1] as $key => $value) { ?>
                            <option value="<?= $key ?>"<?= ($val == $key) ? ' selected' : '' ?>><?= htmlspecialcharsbx(
                                    $value
                                ) ?></option>
                            <?php
                        } ?>
                    </select>
                    <?php
                } ?>
            </td>
        </tr>
        <?php
    }
    ?>

    <tr>
        <td colspan="2">
            <div class="adm-info-message-wrap">
                <div class="adm-info-message">
                    <table>
                        <?php
                        $i = 0;
                        foreach (Options::fileTypes as $key => $value) {
                            ?>
                            <tr>
                                <td>
                                    <span class='required'><sup><?= ++$i ?></sup></span> <?= GetMessage(
                                        $value['lang']
                                    ) ?>
                                </td>
                                <td>
                                    <?= implode(', ', $value['types']) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
        </td>
    </tr>
    <?php
    //</editor-fold>

    $tabControl->Buttons(); ?>
    <input <?= (!$RIGHT_W) ? 'disabled' : '' ?> type="submit" name="Update" value="<?= GetMessage('MAIN_SAVE') ?>"
                                                title="<?= GetMessage('MAIN_OPT_SAVE_TITLE') ?>"
                                                class="adm-btn-save">
    <input <?= (!$RIGHT_W) ? 'disabled' : '' ?> type="submit" name="Apply"
                                                value="<?= GetMessage('MAIN_OPT_APPLY') ?>"
                                                title="<?= GetMessage('MAIN_OPT_APPLY_TITLE') ?>">
    <?php
    if ($_REQUEST['back_url_settings'] <> '') { ?>
        <input <?= (!$RIGHT_W) ? 'disabled' : '' ?> type="button" name="Cancel"
                                                    value="<?= GetMessage('MAIN_OPT_CANCEL') ?>"
                                                    title="<?= GetMessage('MAIN_OPT_CANCEL_TITLE') ?>"
                                                    onclick="window.location='<?= htmlspecialcharsbx(
                                                        CUtil::addslashes($_REQUEST['back_url_settings'])
                                                    ) ?>'">
        <input type="hidden" name="back_url_settings"
               value="<?= htmlspecialcharsbx($_REQUEST['back_url_settings']) ?>">
        <?php
    } ?>
    <input <?= (!$RIGHT_W) ? 'disabled' : '' ?> type="submit" name="RestoreDefaults"
                                                title="<?= GetMessage('MAIN_HINT_RESTORE_DEFAULTS') ?>"
                                                onclick="return confirm('<?= AddSlashes(
                                                    GetMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING')
                                                ) ?>')" value="<?= GetMessage('MAIN_RESTORE_DEFAULTS') ?>">
    <?= bitrix_sessid_post(); ?>
    <?php
    $tabControl->End(); ?>
</form>

<style>
    .adm-info-message-wrap {
        display: flex;
        justify-content: center;
    }

    .cdnnow_buttons_wrap {
        display: flex;
        justify-content: center;
        margin: 15px 0 0;
    }
</style>
