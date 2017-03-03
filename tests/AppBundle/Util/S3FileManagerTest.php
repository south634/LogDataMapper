<?php

namespace Tests\AppBundle\Util;

use AppBundle\Util\S3FileManager;

class S3FileManagerTest extends \PHPUnit_Framework_TestCase
{
    
    public function testConstruct()
    {
        $s3 = $this->getMockBuilder('\Aws\S3\S3Client')
                ->setMethods(array('__construct'))
                ->setConstructorArgs(array(array()))
                ->disableOriginalConstructor()
                ->getMock();
        
        $s3FileManager = new S3FileManager($s3);
        
        $this->assertInstanceOf('\Aws\S3\S3Client', $s3FileManager->s3);
    }

}
