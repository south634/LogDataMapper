<?php

namespace AppBundle\Util;

use Aws\S3\S3Client;

class S3FileManager
{

    /**
     * @var S3Client 
     */
    public $s3;

    /**
     * @param S3Client $s3client
     */
    public function __construct(S3Client $s3client)
    {
        $this->s3 = $s3client;
    }
    
    /**
     * Returns a seekable file stream as SplFileObject
     * 
     * @param array $s3object
     * @return \SplFileObject
     */
    public function getSeekableStreamSplFileObject($s3object)
    {
        $context = stream_context_create([
            's3' => [
                'seekable' => true
            ]
        ]);

        $this->s3->registerStreamWrapper();

        $filePath = "s3://{$s3object['Bucket']}/{$s3object['Key']}";

        return new \SplFileObject($filePath, 'r', false, $context);        
    }

}
