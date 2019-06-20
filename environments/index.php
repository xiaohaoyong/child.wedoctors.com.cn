<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'databackend/runtime',
            'databackend/web/assets',
            'hospital/runtime',
            'hospital/web/assets',
            'weixin/runtime',
            'weixin/web/assets',
            'api/runtime',
            'api/web/assets',
            'interfaces/runtime',
            'interfaces/web/assets',
            'ask/runtime',
            'ask/web/assets',
            'docapi/runtime',
            'docapi/web/assets',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'databackend/config/main-local.php',
            'hospital/config/main-local.php',
            'frontend/config/main-local.php',
            'weixin/config/main-local.php',
            'api/config/main-local.php',
            'interfaces/config/main-local.php',
            'ask/config/main-local.php',
            'docapi/config/main-local.php',

        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'databackend/runtime',
            'databackend/web/assets',
            'hospital/runtime',
            'hospital/web/assets',
            'weixin/runtime',
            'weixin/web/assets',
            'api/runtime',
            'api/web/assets',
            'interfaces/runtime',
            'interfaces/web/assets',
            'ask/runtime',
            'ask/web/assets',
            'docapi/runtime',
            'docapi/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'databackend/config/main-local.php',
            'hospital/config/main-local.php',
            'weixin/config/main-local.php',
            'api/config/main-local.php',
            'interfaces/config/main-local.php',
            'ask/config/main-local.php',
            'docapi/config/main-local.php',
        ],
    ],
];
