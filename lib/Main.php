<?php
/**
 * Класс для работы модуля Интеграции с cdnnow!
 * Выполняет подмену путей на странице на пути cdnnow!
 */

namespace Cdnnow\Core;

use Bitrix\Main\Context;
use COption;

class Main
{
    private static $options;
    private static $active;
    private static $address;
    private static $protocol;
    private static $domain;

    /**
     * Событие модуля Main
     * @param $content string Вывод страницы
     * @return void
     */
    public static function OnEndBufferContent(&$content)
    {
        # Определить домен и протокол
        self::$protocol = $_SERVER['HTTPS'] ? 'https://' : 'http';
        self::$domain   = $_SERVER['SERVER_NAME'];

        # Не применять логику в административной панели
        $request = Context::getCurrent()->getRequest();
        if ($request->isAdminSection()) {
            return;
        }

        if (self::hasBreakingRules()) {
            return;
        }
        self::$options = self::getOptions();

        # Не применять логику, если модуль деактивирован
        if (self::$active !== 'Y' || !self::$address) {
            return;
        }

        $content = self::replaceHTML($content);
    }

    private static function hasBreakingRules()
    {
        $rules = COption::GetOptionString(Options::moduleId, SITE_ID . '_module_cdnnow_rules', '', SITE_ID);
        $rules = json_decode($rules);
        foreach ($rules as $rule) {
            $initialRule = $rule;
            $rule        = str_replace('/', '\/', $rule);
            $rule        = str_replace('*', '.*', $rule);

            if (preg_match("/^\/?{$rule}.*/", $_SERVER['REQUEST_URI'])) {
                require $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
                !d(
                    [
                        'Правило сработало',
                        'Текущий URL'               => $_SERVER['REQUEST_URI'],
                        'Пользовательское правило'  => $initialRule,
                        'В трансформированном виде' => $rule,
                    ]
                );

                return true;
            }
        }
        return false;
    }

    /**
     * Получить настройки модуля из таблицы b_options
     * @return array
     */
    private static function getOptions(): array
    {
        self::$active  = COption::GetOptionString(Options::moduleId, 'module_cdnnow_active', '', SITE_ID);
        self::$address = COption::GetOptionString(Options::moduleId, 'module_cdnnow_address', '', SITE_ID);

        $keys = array_keys(Options::fileTypes);
        foreach ($keys as $key) {
            $options[$key] = COption::GetOptionString(Options::moduleId, 'module_cdnnow_' . $key, '', SITE_ID);
        }
        return $options;
    }

    /**
     * Определение путей нужных типов файлов и их подмена на пути в cdnnow!
     * @param $content string Вывод страницы
     * @return string
     */
    private static function replaceHTML($content)
    {
        foreach (self::$options as $option => $value) {
            if ($value === 'Y' && array_key_exists($option, Options::fileTypes)) {
                foreach (Options::fileTypes[$option]['types'] as $type) {
                    $matches = $urls = $BxJsScripts = $BxCssScripts = [];
                    if ($option == 'images') {
                        # Картинки

                        # Отдельный паттерн для CSS url() без кавычек, только со скобками
                        $pattern = "/(url\(\s?)([^'\"]*?\.{$type})(\s?\))/i";
                        preg_match_all(
                            $pattern,
                            $content,
                            $matches
                        );
                        $urls = array_merge($urls, $matches[2]);

                        # Общий паттерн
                        $pattern = "/((url\(|src\s?=\s?|srcset\s?=\s?|href\+?=\s?)['\"])([^'\"]*?\.{$type})(['\"])/i";
                        preg_match_all(
                            $pattern,
                            $content,
                            $matches
                        );
                        $urls = array_merge($urls, $matches[3]);
                        $urls = array_unique($urls);
                    } elseif ($option == 'js') {
                        # Javascript

                        # Определить включения JS Битриксом - BX.setJSList()
                        $patternJs = '/(<script( type="text\/javascript")?>BX\.setJSList\(\[)(.*?)(\]\);\s?<\/script>)/im';
                        preg_match_all($patternJs, $content, $matches);
                        if (is_array($matches[3])) {
                            foreach ($matches[3] as $match) {
                                $jsScripts = explode(',', $match);
                                foreach ($jsScripts as &$el) {
                                    $el = str_replace("'", '', $el);
                                }
                                unset($el);
                                $BxJsScripts = array_merge($BxJsScripts, $jsScripts);
                            }
                        }

                        # Определить ссылки JS из тега <script>
                        $pattern = "/((src\s?=\s?|href\s?=\s?)['\"])([^'\"]*?\.{$type}(\?.*?))(['\"])/i";
                        preg_match_all($pattern, $content, $matches);
                        $urls = array_merge($BxJsScripts, $matches[3]);
                        $urls = array_unique($urls);
                    } elseif ($option == 'css') {
                        # CSS

                        # Определить включения CSS Битриксом - BX.setCSSList()
                        $patternCss = '/(<script( type="text\/javascript")?>BX\.setCSSList\(\[)(.*?)(\]\);\s?<\/script>)/im';
                        preg_match_all($patternCss, $content, $matches);
                        if (is_array($matches[3])) {
                            foreach ($matches[3] as $match) {
                                $cssScripts = explode(',', $match);
                                foreach ($cssScripts as &$el) {
                                    $el = str_replace("'", '', $el);
                                }
                                unset($el);
                                $BxCssScripts = array_merge($BxCssScripts, $cssScripts);
                            }
                        }

                        # Определить ссылки CSS
                        $pattern = "/((src\s?=\s?|href\s?=\s?)['\"])([^'\"]*?\.{$type}(\?.*?))(['\"])/i";
                        preg_match_all($pattern, $content, $matches);
                        $urls = array_merge($BxCssScripts, $matches[3]);
                        $urls = array_unique($urls);
                    } else {
                        # Остальные типы файлов

                        $pattern = "/((src\s?=\s?|srcset\s?=\s?|href\+?=\s?)['\"])([^'\"]*?\.{$type})(['\"])/i";
                        preg_match_all($pattern, $content, $matches);
                        $urls = $matches[3];
                        $urls = array_unique($urls);
                    }
                    $content = self::replaceUrls($content, $urls);
                }
            }
        }

        return $content;
    }

    /**
     * Заменить пути на вариант от cdnnow!
     * @param $content string Вывод страницы
     * @param $urls    array Массив путей
     * @return array|mixed|string|string[]
     */
    private static function replaceUrls($content, $urls)
    {
        foreach ($urls as &$url) {
            # Удалить домен из ссылки
            if (strpos($url, self::$protocol . self::$domain) !== false) {
                $url = str_replace(self::$protocol . self::$domain, '', $url);
            }

            # Заменить урл на cdn, если домена cdn ещё нет в урле
            if (strpos($url, self::$address) === false) {
                $content = str_replace($url, self::$protocol . self::$address . $url, $content);
            }
        }

        return $content;
    }
}
