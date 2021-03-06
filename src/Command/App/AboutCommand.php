<?php

/**
 * @file
 * Contains \Docker\Drupal\Command\AboutCommand.
 */

namespace Docker\Drupal\Command\App;

use Docker\Drupal\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Docker\Drupal\Style\DruDockStyle;

/**
 * Class AboutCommand
 *
 * @package Docker\Drupal\Command
 */
class AboutCommand extends Command
{

    protected function configure()
    {
        $this
        ->setName('self:about')
        ->setAliases(['about'])
        ->setDescription('About DruDock')
        ->setHelp("Output general INFO");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = new Application();
        $io = new DruDockStyle($input, $output);

        if ($dockerversion = $application->getDockerVersion()) {
            $dv = $dockerversion;
        } else {
            $dv = 'Docker version [UNKNOWN]';
        }

        $io->info(' ');
        $io->title('DruDock [cli] - ' . $application->getVersion() . ' ::: Create and manage Drupal projects with Docker ::: ' . $dv);

        $io->info(' ');
        $io->section(' Docker status ');

        if ($application->checkDocker($input, $output)) {
            $io->success(' Docker Running');
        } else {
            $io->error(' Docker Not found');
        }

        $io->info(' ');
        $io->section(' PHP version ');
        $this->checkPHPVersion($io);

        $io->info(' ');
        $io->section(' GIT installed ');
        $this->checkGitInstalled($io);

        $io->info(' ');
        $io->section('Get started');

        $io->simple('SETUP DOCKER ENVIRONMENT');
        $io->info(' ');
        $io->info('     drudock app:init');
        $io->info(' ');

        $io->simple('BUILD DRUPAL 8 APP');
        $io->info(' ');
        $io->info('     drudock app:init:build --appname d8newpp_development --type D8 --dist Development --src New --apphost testd8.drudock.localhost --services "PHP,NGINX,MYSQL"');
        $io->info(' ');


        $io->simple('BUILD DRUPAL 7 APP');
        $io->info(' ');
        $io->info('     drudock app:init:build --appname d7newpp_development --type D7 --dist Development --src New --apphost testd7.drudock.localhost --services "PHP,NGINX,MYSQL"');
        $io->info(' ');

        $io->simple('AVAILABLE COMMANDS');
        $io->info(' ');
        $io->info('     drudock list');
        $io->info(' ');
    }

  /**
   * @return string
   */
    private function checkPHPVersion($io)
    {

        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
            $io->success(' PHP VERSION :: ' . PHP_VERSION);
        } else {
            $io->warning(' PHP VERSION is > 5.5.0 and should be upgraded.');
        }
    }

  /**
   * @param object
   */
    private function checkGitInstalled($io)
    {
        exec('which git', $output);
        if ($output) {
            $io->success('GIT installed');
        } else {
            $io->error('GIT not found. Please install.');
        }
    }
}
