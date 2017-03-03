<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use AppBundle\Entity\LogData;

class LogProcessCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('log:process')
                ->setDescription('Map IP and user agent data in log.')
                ->setHelp('This command allows you to map IP address and user agent data to database.')
                ->addOption('min-datetime', null, InputOption::VALUE_OPTIONAL)
                ->addOption('max-datetime', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // retrieve command line input for date range filtering
        $minDateTime = $input->getOption('min-datetime') ? new \DateTime($input->getOption('min-datetime')) : null;
        $maxDateTime = $input->getOption('max-datetime') ? new \DateTime($input->getOption('max-datetime')) : null;

        // minDateTime cannot greater than maxDateTime
        if (($minDateTime && $maxDateTime) && ($minDateTime > $maxDateTime)) {
            $output->write('--min-datetime cannot be larger than --max-datetime. Please try again.');
            $this->callExit();
        }

        // get services
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $logParser = $this->getContainer()->get('kassner_log_parser.log_parser');
        $logDataMapper = $this->getContainer()->get('app.log_data_mapper');
        $s3FileManager = $this->getContainer()->get('app.s3_file_manager');
        
        // get settings of s3object to stream
        $s3object = $this->getContainer()->getParameter('s3object');
        
        // seekable SplFileObject for stream
        $file = $s3FileManager->getSeekableStreamSplFileObject($s3object);
        $file->setFlags(\SplFileObject::DROP_NEW_LINE);

        // get total lines in file
        $file->seek(PHP_INT_MAX);
        $lineCount = $file->key() + 1;
        $file->rewind();

        $output->writeln('Processing log file...');

        // start progress bar
        $progress = new ProgressBar($output, $lineCount);
        $progress->start();
        
        // batch size for database inserts
        $batchSize = 100;
        // while loop counter
        $i = 0;
        while (!$file->eof()) {

            $line = $file->fgets();

            if (empty($line)) {
                // skip empty lines
                $progress->advance();
                continue;
            }

            // parse log entry to object with line data
            $entry = $logParser->parse($line);

            // get datetime of entry
            $dateTime = new \DateTime($entry->time);

            // skip entry if datetime not in range
            if (($minDateTime && $dateTime < $minDateTime) || ($maxDateTime && $dateTime > $maxDateTime)) {
                $progress->advance();
                continue;
            }

            // get ip address and map it to geoIpData array
            $ip = $entry->host;
            $geoIpData = $logDataMapper->mapIpToGeoData($ip);

            // set user agent to null if not present
            $userAgent = $entry->HeaderUserAgent ? $entry->HeaderUserAgent : null;
            // map user agent to device data array
            $userAgentData = $logDataMapper->mapUserAgentToDeviceData($userAgent);

            // merge arrays
            $mappedData = array_merge($geoIpData, $userAgentData);

            // set mapped data to new LogData entity
            $logData = new LogData();
            $logData->setLatitude($mappedData['latitude']);
            $logData->setLongitude($mappedData['longitude']);
            $logData->setCountry($mappedData['country']);
            $logData->setState($mappedData['state']);
            $logData->setCity($mappedData['city']);
            $logData->setZipCode($mappedData['zipCode']);
            $logData->setBrowser($mappedData['browser']);
            $logData->setDeviceType($mappedData['deviceType']);
            $logData->setOs($mappedData['os']);

            $em->persist($logData);

            // flush if batch size met
            if ($i % $batchSize === 0) {
                $em->flush();
                $em->clear();
            }

            $progress->advance();

            $i++;
        }

        // flush any left overs
        $em->flush();
        $em->clear();

        $file = null;
        $logDataMapper = null;

        // done
        $progress->finish();
        
        $output->writeln('');
        $output->writeln('Done');
        $output->writeln('');

    }

    protected function callExit()
    {
        exit;
    }

}
