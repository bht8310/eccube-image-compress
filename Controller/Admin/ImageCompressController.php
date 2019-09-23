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

namespace Plugin\ImageCompress\Controller\Admin;

use Plugin\ImageCompress\Entity\ImageCompress;
use Plugin\ImageCompress\Form\Type\Admin\ImageCompressConfigType;
use Plugin\ImageCompress\Repository\ImageCompressRepository;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImageCompressController.
 */
class ImageCompressController extends AbstractController
{
    /**
     * @var ImageCompressRepository
     */
    protected $imageCompressRepository;

    /**
     * @var ImageCompress
     */
    protected $imageCompressConfig;

    /**
     * ImageCompressController constructor.
     *
     * @param ImageCompressRepository $imageCompressRepository
     */
    public function __construct(
        ImageCompressRepository $imageCompressRepository
    ) {
        $this->imageCompressRepository = $imageCompressRepository;
        $this->imageCompressConfig = $this->imageCompressRepository->get();

    }

    /**
     * search product modal.
     *
     * @param Request $request
     * @param int $page_no
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     *
     * @Route("/%eccube_admin_route%/image_compress/config", name="image_compress_admin_config")
     *
     * @Template("@ImageCompress/admin/config.twig")
     */
    public function config(Request $request){

        $builder = $this->formFactory
            ->createBuilder(ImageCompressConfigType::class, $this->imageCompressConfig);
        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->imageCompressConfig->setEnable($form->get('enable')->getData());
            $this->imageCompressConfig->setApiKey($form->get('api_key')->getData());

            $this->imageCompressRepository->save($this->imageCompressConfig);
            $this->entityManager->flush();
        }

        return [
            'form' => $form->createView()
        ];
    }

}
