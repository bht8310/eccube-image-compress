<?php

/*
 */

namespace Plugin\ImageCompress\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\ImageCompress\Entity\ImageCompress;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * ImageCompressRepository.
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageCompressRepository extends AbstractRepository
{
    /**
     * ImageCompressRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageCompress::class);
    }


    public function get($id = 1){
        $imageCompress = $this->find($id);
        if($imageCompress) return $imageCompress;
        return new ImageCompress();
    }
}
