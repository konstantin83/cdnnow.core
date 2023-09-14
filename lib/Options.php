<?php
/**
 * Класс, содержащий модель данных, с которыми работает модуль Интеграции с cdnnow!
 */

namespace Cdnnow\Core;

class Options
{
    const moduleId  = 'cdnnow.core';
    const fileTypes = [
        'images'  =>
            [
                'lang'  => 'CDNNOW_OPTIONS_IMAGES',
                'types' =>
                    [
                        'bmp',
                        'gif',
                        'ico',
                        'jpeg',
                        'jpg',
                        'png',
                        'svg',
                        'tif',
                        'tiff',
                        'webp',
                    ],
            ],
        'css'     =>
            [
                'lang'  => 'CDNNOW_OPTIONS_CSS',
                'types' =>
                    [
                        'css',
                    ],
            ],
        'js'      =>
            [
                'lang'  => 'CDNNOW_OPTIONS_JS',
                'types' =>
                    [
                        'js',
                    ],
            ],
        'fonts'   =>
            [
                'lang'  => 'CDNNOW_OPTIONS_FONT',
                'types' =>
                    [
                        'otf',
                        'ttf',
                        'woff',
                        'woff2',
                    ],
            ],
        'archive' =>
            [
                'lang'  => 'CDNNOW_OPTIONS_ARCHIVE',
                'types' =>
                    [
                        'gz',
                        'rar',
                        'z',
                        'zip',
                    ],
            ],
        'audio'   =>
            [
                'lang'  => 'CDNNOW_OPTIONS_AUDIO',
                'types' =>
                    [
                        'aac',
                        'flac',
                        'mp3',
                        'ogg',
                        'wav',
                    ],
            ],
        'video'   =>
            [
                'lang'  => 'CDNNOW_OPTIONS_VIDEO',
                'types' =>
                    [
                        'avi',
                        'flv',
                        'mkv',
                        'mp4',
                        'mpeg',
                        'oga',
                        'ogv',
                        'ogx',
                        'vob',
                        'webm',
                    ],
            ],
        'embeded' =>
            [
                'lang'  => 'CDNNOW_OPTIONS_EMBEDED',
                'types' =>
                    [
                        'swf',
                    ],
            ],
        'objects' =>
            [
                'lang'  => 'CDNNOW_OPTIONS_OBJECT',
                'types' =>
                    [
                        'json',
                        'wsdl',
                        'xsd',
                        'xml',
                    ],
            ],
        'doc'     =>
            [
                'lang'  => 'CDNNOW_OPTIONS_DOC',
                'types' =>
                    [
                        'csv',
                        'doc',
                        'docx',
                        'odp',
                        'ods',
                        'odt',
                        'pdf',
                        'pps',
                        'ppsm',
                        'ppsx',
                        'ppt',
                        'pptm',
                        'pptx',
                        'sldm',
                        'sldx',
                        'txt',
                        'xls',
                        'xlsx',
                    ],
            ],
        'exe'     =>
            [
                'lang'  => 'CDNNOW_OPTIONS_EXE',
                'types' =>
                    [
                        'com',
                        'exe',
                        'apk',
                    ],
            ],
    ];
}
