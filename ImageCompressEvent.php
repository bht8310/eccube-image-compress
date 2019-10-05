<?php

/*
 * 画像圧縮プラグイン
 */

namespace Plugin\ImageCompress;

use Plugin\ImageCompress\Repository\ImageCompressRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageCompressEvent implements EventSubscriberInterface
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ImageCompressRepository
     */
    protected $imageCompressRepository;

    /**
     * @var ImageCompress
     */
    protected $imageCompressConfig;

    /**
     * ImageCompressEvent constructor.
     * @param ImageCompressRepository $imageCompressRepository
     */
    public function __construct(
        ImageCompressRepository $imageCompressRepository,
        EccubeConfig $eccubeConfig
    ){
        $this->imageCompressRepository =$imageCompressRepository;
        $this->imageCompressConfig = $this->imageCompressRepository->get();
        $this->eccubeConfig = $eccubeConfig;
    }

    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::ADMIN_PRODUCT_ADD_IMAGE_COMPLETE => 'doCompress',
        ];
    }

    /**
     * 画像圧縮処理の呼び出し
     *
     * @param EccubeEvents $event
     */
    public function doCompress(EventArgs $event)
    {
        $files = $event->getArgument('files');
        if(! is_array($files)){
            return ;
        }

        if(! $this->imageCompressConfig->isDoable()){
            return ;
        }

        $this->getCompressedFile($files);

        return false;
    }

    /**
     * 画像圧縮処理の実施
     *
     * @param EccubeEvents $event
     */
    private function getCompressedFile($files){
        /* APIキーの設定 */
        $api_key = $this->imageCompressConfig->getApiHeaderValue();
        $api_url = 'https://api.tinify.com/shrink';

        $header = [
            'Authorization: Basic '.$api_key
        ];

        foreach($files as $file){
            $curl = curl_init();

            $file_path = $this->eccubeConfig['eccube_temp_image_dir']."/".$file;
            if(!file_exists($file_path)) continue;

            if(!$this->imageCompressConfig->isEnableExt($file))continue;

            $uncompressed_file = file_get_contents($file_path);

            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $uncompressed_file);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            if ( curl_errno($curl)!==CURLE_OK ) {
                continue;
            }
            $result = json_decode($response);
            
            curl_close($curl);
            if(isset( $result->output->url)){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $result->output->url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $compressed_file = curl_exec($curl);

                if ( curl_errno($curl)!==CURLE_OK ) {
                    continue;
                }
                curl_close($curl);

                file_put_contents($file_path,$compressed_file);
            }
        }
    }

}
