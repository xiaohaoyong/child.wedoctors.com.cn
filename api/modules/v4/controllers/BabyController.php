<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/30
 * Time: 上午11:51
 */

namespace api\modules\v4\controllers;

use api\controllers\Controller;

use common\models\BabyGuide;
use common\models\BabyTool;
use common\models\BabyToolLike;
use common\models\BabyToolTag;
use common\models\Vaccine;

class BabyController extends \api\controllers\BabyController
{

    public function actionVlist()
    {

        $ages = [
            [
                'name' => '一月龄',
                'vaccines' => [
                    'left' => [
                        [

                            'url' => '/pages/tool/vview/index?id=3',
                            'name' => '乙肝疫苗(第2剂)',
                        ]
                    ],
                    'right' => [
                    ],

                ]
            ],
            [
                'name' => '二月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=4',
                            'name' => '脊灰疫苗(IPV)(第1剂)',
                        ],
                    ],
                    'right' => [
                        [
                            'url' => '/pages/article/view/index?id=1983',
                            'id'=>1983,

                            'name' => '五联疫苗',
                        ],
                    ],

                ]
            ],
            [
                'name' => '三月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=6',
                            'name' => '无细胞百白破疫苗	',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=5',
                            'name' => '脊灰疫苗(IPV)(第2剂)',
                        ],
                    ],
                    'right' => [
                        [
                            'url' => '/pages/article/view/index?id=1983',
                            'id'=>1983,

                            'name' => '五联疫苗',
                        ],
                    ],

                ]
            ],
            [
                'name' => '四月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=8',
                            'name' => '无细胞百白破疫苗	',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=7',
                            'name' => '脊灰疫苗(OPV)(第3剂)',
                        ],
                    ],
                    'right' => [
                        [
                            'url' => '/pages/article/view/index?id=1983',
                            'id'=>1983,

                            'name' => '五联疫苗',
                        ],
                    ],

                ]
            ],
            [
                'name' => '五月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=9',
                            'name' => '无细胞百白破疫苗(第3剂)	',
                        ],
                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '六月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=11',
                            'name' => 'A群流脑疫苗',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=10',
                            'name' => '乙肝疫苗(第3剂)',
                        ],
                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '八月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=16',
                            'name' => '麻腮风疫苗(第1剂)',
                        ],
                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '九月龄',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=14',
                            'name' => 'A群流脑疫苗(第2剂)',
                        ],
                    ],
                    'right' => [],
                ]
            ],
            [
                'name' => '一岁',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=13',
                            'name' => '乙脑减毒疫苗(第1剂)	',
                        ],
                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '一岁半',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=21',
                            'name' => '麻腮风疫苗(第1剂)',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=15',
                            'name' => '无细胞百白破疫苗',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=17',
                            'name' => '甲肝疫苗(第1剂)',
                        ],
                    ],
                    'right' => [
                        [
                            'url' => '/pages/article/view/index?id=1983',
                            'id'=>1983,

                            'name' => '五联疫苗',
                        ],
                    ],

                ]
            ],
            [
                'name' => '二岁',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=18',
                            'name' => '乙脑减毒疫苗	',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=23',
                            'name' => '甲肝疫苗(第2剂)',
                        ],
                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '三岁',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=19',
                            'name' => 'A+C群流脑疫苗(第1剂)',
                        ],
                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '四岁',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=20',
                            'name' => '脊灰疫苗(OPV)(第4剂)',
                        ],

                    ],
                    'right' => [],

                ]
            ],
            [
                'name' => '六岁',
                'vaccines' => [
                    'left' => [
                        [
                            'url' => '/pages/tool/vview/index?id=42',
                            'name' => 'A+C群流脑疫苗',
                        ],
                        [
                            'url' => '/pages/tool/vview/index?id=22',
                            'name' => '白破疫苗(DT)',
                        ],
                    ],
                    'right' => [],

                ]
            ],

        ];
        //$list = Vaccine::find()->where(['>', 'source', 0])->orderBy('source asc')->all();
        return array_reverse($ages);
    }
}