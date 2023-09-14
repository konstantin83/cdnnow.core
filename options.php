<?php

use Cdnnow\Core\Options;

$module_id  = Options::moduleId;
$modulePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__);

global $USER;
$RIGHT_R = $RIGHT_W = $USER->IsAdmin();

if (!$RIGHT_W && !$RIGHT_R) {
    return;
}

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/main/options.php');
IncludeModuleLangFile(__FILE__);

$mainOptions = [
    'module_cdnnow_active'  => [
        'module_cdnnow_active',
        GetMessage('CDNNOW_OPTIONS_ACTIVE'),
        ['checkbox'],
    ],
    'module_cdnnow_address' => [
        'module_cdnnow_address',
        GetMessage('CDNNOW_OPTIONS_ADDRESS'),
        ['text', '30'],
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

/* # Вступительная вкладка
$aTabs = [
    [
        'DIV'   => 'main',
        'TAB'   => 'Подключение CDN',
        'ICON'  => 'biconnector_settings',
//        'TITLE' => 'Подключение CDN',
        'TITLE' => GetMessage('CDNNOW_OPTIONS_HEADER_TOP'),
    ],
];*/

# Получить список сайтов
$res = CSite::GetList();
while ($site = $res->Fetch()) {
    $aTabs[] = [
        'DIV'   => $site['LID'],
        'TAB'   => "CDN для «{$site['NAME']}»",
        'ICON'  => 'biconnector_settings',
        'TITLE' => "{$site['NAME']}",
    ];
}

$tabControl = new CAdminTabControl('tabControl', $aTabs);

\Bitrix\Main\UI\Extension::load($module_id);
\Bitrix\Main\UI\Extension::load('ui.buttons');

//<editor-fold default-state='collapsed' desc="Обработка POST запроса">
if ($REQUEST_METHOD == 'POST' && $Update . $Apply . $RestoreDefaults <> '' && $RIGHT_W && check_bitrix_sessid()) {
    if ($RestoreDefaults <> '') {
        COption::RemoveOption($module_id);
    } else {
        foreach ($aTabs as $tab) {
            foreach ($mainOptions as $option) {
                $name  = $option[0];
                $dirty = $tab['DIV'] . '_' . $name;
                $val   = trim($_REQUEST[$tab['DIV'] . '_' . $name], " \t\n\r");
                if ($option[2][0] == 'checkbox' && $val != 'Y') {
                    $val = 'N';
                }

                $val = trim($val);
                if ($name == 'module_cdnnow_address' && strpos($val, 'http') !== false) {
                    $val = preg_replace('/^http(s)?:\/\//', '', $val);
                }
                COption::SetOptionString($module_id, $name, $val, $option[1], $tab['DIV']);
            }

            foreach ($fileTypeOptions as $option) {
                $name  = $option[0];
                $dirty = $tab['DIV'] . '_' . $name;
                $val   = trim($_REQUEST[$tab['DIV'] . '_' . $name], " \t\n\r");
                if ($option[2][0] == 'checkbox' && $val != 'Y') {
                    $val = 'N';
                }
                COption::SetOptionString($module_id, $name, $val, $option[1], $tab['DIV']);
            }

            $name  = "{$tab['DIV']}_module_cdnnow_rules";
            $rules = $_REQUEST[$name];
            if ($rules) {
                $rulesJson = json_encode($rules);
                COption::SetOptionString($module_id, $name, $rulesJson, '', $tab['DIV']);
            }
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

//<editor-fold desc="Функции">

function showMain(array $tab)
{
    ?>
    <div class="adm-info-message-wrap">
        <div class="adm-info-message">
            <?= GetMessage('CDNNOW_OPTIONS_MAIN_DESC') ?>
        </div>
    </div>
    <?php
}

function showDefault(array $tab)
{
    global $mainOptions, $fileTypeOptions, $module_id, $modulePath;
    ?>
    <div class="adm-info-message-wrap">
        <div class="adm-info-message" style="margin-top: 0;">
            <?= GetMessage('CDNNOW_OPTIONS_MAIN_DESC') ?>
        </div>
    </div>

    <tr class="heading">
        <td colspan="2"><b><?= GetMessage('CDNNOW_OPTIONS_HEADER_MAIN') ?></b></td>
    </tr>

    <?php
    foreach ($mainOptions as $k => $option) {
        $val  = COption::GetOptionString($module_id, $option[0], '', $tab['DIV']);
        $type = $option[2];

        if ($option[0] === 'module_cdnnow_active') {
            $option[1] .= " <br>«{$tab['TITLE']}»";
        }
        ?>
        <tr>
            <td <?= ($type[0] == 'textarea') ? 'class="adm-detail-valign-top"' : '' ?>>
                <label for="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"><?= $option[1] ?>:</label>
            <td>
                <?php
                if ($type[0] == 'checkbox') { ?>
                    <input type="checkbox" name="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"
                           id="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"
                           value="Y"<?= ($val == 'Y') ? ' checked' : '' ?>>
                    <?php
                } elseif ($type[0] == 'text') { ?>
                    <input type="text" size="<?= $type[1] ?>" maxlength="255"
                           value="<?= htmlspecialcharsbx($val) ?>"
                           name="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"
                           id="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>">
                    <?php
                } elseif ($type[0] == 'textarea') { ?>
                    <textarea rows="<?= $type[1] ?>" cols="<?= $type[2] ?>"
                              name="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"
                              id="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"><?= htmlspecialcharsbx(
                            $val
                        ) ?></textarea>
                    <?php
                } elseif ($type[0] == 'selectbox') { ?>
                    <select name="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>">
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
                    <?= GetMessage('CDNNOW_OPTIONS_ADDRESS_DESC') ?>
                </div>
            </div>
        </td>
    </tr>
    <tr class="heading">
        <td colspan="2"><b><?= GetMessage('CDNNOW_OPTIONS_HEADER_TYPES') ?></b></td>
    </tr>

    <tr class="tr_all_checkboxes">
        <td>
            <input type="checkbox" id="<?= $tab['DIV'] ?>_all_checkboxes" class="all_checkboxes"
                   data-site_id="<?= $tab['DIV'] ?>">
        </td>
        <td <?= ($type[0] == 'textarea') ? 'class="adm-detail-valign-top"' : '' ?>>
            <label for="<?= $tab['DIV'] ?>_all_checkboxes">
                Выбрать все
            </label>
        </td>
    </tr>

    <?php
    foreach ($fileTypeOptions as $option) {
        $val  = COption::GetOptionString($module_id, $option[0], '', $tab['DIV']);
        $type = $option[2];
        ?>
        <tr>
            <td>
                <input type="checkbox"
                       class="<?= $tab['DIV'] ?>_cdn_type"
                       name="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"
                       id="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"
                       value="Y"<?= ($val == 'Y') ? ' checked' : '' ?>>
            </td>
            <td <?= ($type[0] == 'textarea') ? 'class="adm-detail-valign-top"' : '' ?>>
                <label for="<?= htmlspecialcharsbx($tab['DIV'] . '_' . $option[0]) ?>"><b><?= $option[1] ?></b>
                    (<?= $option['hint'] ?>)</label>
            </td>
        </tr>
        <?php
    }
    ?>

    <tr class="heading">
        <td colspan="2"><b><?= GetMessage('CDNNOW_OPTIONS_RULES_TITLE') ?></b></td>
    </tr>

    <tr>
        <td class="adm-detail-content-cell-l">
            <label for="<?= $tab['DIV'] ?>_module_cdnnow_rules">
                <?= GetMessage('CDNNOW_OPTIONS_RULES_LABEL') ?>:
            </label>
        </td>
        <td class="adm-detail-content-cell-r">
            <?php
            $rulesRes = COption::GetOptionString($module_id, "{$tab['DIV']}_module_cdnnow_rules", '', $tab['DIV']);
            if ($rulesRes) {
                $pathRules = json_decode($rulesRes, true);
            } else {
                $pathRules = [''];
            }

            foreach ($pathRules as $pathRuleId => $pathRule) {
                $minusDisplay = count($pathRules) === 1 ? 'none' : '';
                ?>
                <div class="path_wrap" data-path-id="<?= $pathRuleId ?>">
                    <input type="text" size="30" maxlength="255" value="<?= $pathRule ?>"
                           name="<?= $tab['DIV'] ?>_module_cdnnow_rules[]" id="<?= $tab['DIV'] ?>_module_cdnnow_rules">

                    <div class="path_operands">

                        <img src="<?= $modulePath . '/images/minus.svg' ?>" alt=""
                             title="Удалить правило"
                             data-action="minus"
                             style="display:<?= $minusDisplay ?>;"
                             class="path_rules minus">

                        <img src="<?= $modulePath . '/images/plus.svg' ?>" alt=""
                             title="Добавить правило"
                             data-action="plus"
                             style="display: <?= (count($pathRules) === $pathRuleId + 1) ? '' : 'none' ?>"
                             class="path_rules plus">

                    </div>
                </div>
                <?php
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="adm-info-message-wrap">
                <div class="adm-info-message">
                    <?= GetMessage('CDNNOW_OPTIONS_RULES_DESC') ?>
                </div>
            </div>
        </td>
    </tr>
    <?php
}

//</editor-fold>

?>
<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($module_id) ?>&amp;lang=<?= LANGUAGE_ID ?>">

    <?php
    $tabControl->Begin();

    foreach ($aTabs as $tab) {
        $tabControl->BeginNextTab();

        switch ($tab['DIV']) {
            case 'main':
                showMain($tab);
                break;
            default:
                showDefault($tab);
                break;
        }
    }
    //<editor-fold desc="Кнопки">
    $tabControl->Buttons();

    ?>
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
    //</editor-fold>

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

    .tr_all_checkboxes td {
        padding-bottom: 1.5em;
    }

    .path_operands {
        display: inline-block;
        margin-left: 5px;
    }

    .path_operands img {
        width: 22px;
        margin: 0 3px;
        vertical-align: middle;
        cursor: pointer;
    }

    .adm-detail-content-cell-l {
        min-width: 200px;
    }
</style>

<script>
  BX.ready(function () {
    BX.bindDelegate(document.body, 'click', {
      className: 'all_checkboxes'
    }, function () {
      if (this.tagName !== 'INPUT') return;

      let site_id = this.dataset.site_id;
      if (typeof site_id === 'undefined') return;

      let checkboxes = document.querySelectorAll('input.' + site_id + '_cdn_type');
      checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
    });

    function controlOperands(node) {
      let rules = node.querySelectorAll('.path_wrap')
      let count = rules.length;

      rules.forEach((rule, i) => {
        if (count === 1) {
          rule.querySelector('.minus').style.display = 'none';
        } else {
          rule.querySelector('.minus').style.display = '';
        }

        if (i + 1 === count) {
          rule.querySelector('.plus').style.display = '';
        } else {
          rule.querySelector('.plus').style.display = 'none';
        }

      });
    }

    BX.bindDelegate(document.body, 'click', {
      className: 'path_rules'
    }, function () {

      let parentTd = this.closest("td");
      let pathId   = this.parentNode.parentNode.dataset.pathId;
      let pathNode = this.parentNode.parentNode;
      let action   = this.dataset.action;

      switch (action) {
        case 'minus':
          pathNode.remove();
          controlOperands(parentTd);
          break;
        case 'plus':
          let newRule = pathNode.cloneNode(true);

          newRule.querySelector('input').value = '';
          parentTd.appendChild(newRule);
          controlOperands(parentTd);
          break;
      }

      let site_id = this.dataset.site_id;
      if (typeof site_id === 'undefined') return;

      let checkboxes = document.querySelectorAll('input.' + site_id + '_cdn_type');
      checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
    });
  });
</script>
