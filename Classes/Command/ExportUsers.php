<?php
namespace Hebotek\HeboFeuserExport\Command;

use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExportUsers extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Run content importer. Without arguments all available wizards will be run.')
        ->addArgument(
            'FolderName',
            InputArgument::OPTIONAL,
            'Name of the folder where the file must be saved.'
        )->addArgument(
            'FileName',
            InputArgument::OPTIONAL,
            'Name of the file to create.'
        );
    }

    /**
     * Executes the command for showing sys_log entries
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('fe_users');

        $queryBuilder = $connection->createQueryBuilder();
        $query = $queryBuilder
            ->select('*')
            ->from('fe_users');

        $rows = $query->execute()->fetchAll();

        $folder = ($input->getArgument('FolderName') !== null) ? '/var/www/html/fileadmin/'.$input->getArgument('FolderName').'/' : '/var/www/html/fileadmin/' ;
        if($input->getArgument('FolderName') !== null)
        {
            $folder = $input->getArgument('FolderName');
            $folder = str_replace(' ', '-', $folder);
            $folder = preg_replace('/[^A-Za-z0-9\-]/', '', $folder);
            $folder = '/var/www/html/fileadmin/'.$folder.'/';
        }
        else
        {
            $folder = '/var/www/html/fileadmin/';
        }

        $file = ($input->getArgument('FileName') !== null) ? preg_replace(str_replace(' ', '-', $input->getArgument('FileName'))).'.csv' : 'users.csv' ;

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $fp = fopen($folder.$file, 'w');
        foreach($rows as $key => $row)
        {
            $users[$row['uid']]['id'] = $row['uid'];
            $users[$row['uid']]['firstname'] = $row['first_name'];
            $users[$row['uid']]['lastname'] = $row['last_name'];
            $users[$row['uid']]['email'] = $row['email'];
            $users[$row['uid']]['personal_number'] = isset($row['personal_number']) ? $row['personal_number'] : null;
            fputcsv($fp, $users[$row['uid']]);
        }
        fclose($fp);
        chown($folder.$file, 'www-data');
        //chmod($folder.$file, 0777);
        $io->writeln('File saved '.$folder);
        return Command::SUCCESS;
    }
}