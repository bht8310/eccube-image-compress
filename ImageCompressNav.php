<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plugin\ImageCompress;
use Eccube\Common\EccubeNav;
class ImageCompressNav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'setting' => [
                'children'=>[
                    'system' => [
                        'children'=>[
                            'image_compress' => [
                                'name' => 'image_compress.name',
                                'url' => 'image_compress_admin_config',
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }
}
