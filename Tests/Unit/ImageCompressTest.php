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

namespace Plugin\ImageCompress\Tests\Unit;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Plugin\ImageCompress\Entity\ImageCompress;
use Plugin\ImageCompress\Repository\ImageCompressRepository;
use Eccube\Common\Constant;

/**
 * Class ImageCompressTest.
 */
class ImageCompressTest extends AbstractAdminWebTestCase
{
    /**
     * @var ImageCompressRepository
     */
    protected $imageCompressRepository;

    /**
     * call parent setUp.
     */
    public function setUp()
    {
        parent::setUp();
        $this->imageCompressRepository = $this->container->get(ImageCompressRepository::class);
    }

    /**
     * test do able function
     */
    public function testDoable(){
        $imageCompress = new ImageCompress();

        $imageCompress->setEnable(false);
        $imageCompress->setApiKey("dummy");
        $this->assertEquals($imageCompress->isDoable(),false);

        $imageCompress->setEnable(true);
        $imageCompress->setApiKey("");
        $this->assertEquals($imageCompress->isDoable(),false);

        $imageCompress->setEnable(true);
        $imageCompress->setApiKey("dummy");
        $this->assertEquals($imageCompress->isDoable(),true);
    }

    /**
     * test api key hash
     */
    public function testApiHeaderValue(){
        $imageCompress = new ImageCompress();
        $imageCompress->setApiKey('dummy');

        $api_key = base64_encode('api:'.$imageCompress->getApiKey());
        $this->assertEquals($imageCompress->getApiHeaderValue(),$api_key);
    }

    public function testFileExt(){
        $imageCompress = new ImageCompress();

        $this->assertEquals($imageCompress->isEnableExt("test.jpg"),true);
        $this->assertEquals($imageCompress->isEnableExt("test.JPEG"),true);

        $this->assertEquals($imageCompress->isEnableExt("test.png"),true);
        $this->assertEquals($imageCompress->isEnableExt("test.PNG"),true);

        $this->assertEquals($imageCompress->isEnableExt("test.gif"),false);
        $this->assertEquals($imageCompress->isEnableExt("test.GIF"),false);
    }
}
