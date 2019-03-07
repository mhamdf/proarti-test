<?php

namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Stopwatch\Stopwatch;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Utils\Validator;
use App\Service\LoadCsv;



class LoadCsvCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:load-csv';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $validator;
    private $loadcsv;

    public function __construct( Validator $validator,LoadCsv $loadcsv)
    {
      parent::__construct();

      $this->validator = $validator;
      $this->loadcsv = $loadcsv;
    }

    protected function configure()
    {
      $this
      // the short description shown while running "php bin/console list"
      ->setDescription('Load data from a CSV file')
      ->setHelp($this->getCommandHelp())
      ->addArgument('path', InputArgument::OPTIONAL, 'The path of a csv file!')
       ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // $path = 'the path of file is: '.$input->getArgument('path');
        //
        // $output->writeln($path.' !');
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('path')) {
            return;
        }

        $this->io->title('Load CSV File Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            ' \'path\' argument required by this command as follows:',
            '',
            ' $ php bin/console app:load-csv path',
            '',
            'Now we\'ll ask you for the value of the missing command argument.',
        ]);

        // Ask for the path if it's not defined
        $path = $input->getArgument('path');
        if (null !== $path) {
            $this->io->text(' > <info>Path</info>: '.$path);
        } else {
            $path = $this->io->ask('Path', null, [$this->validator, 'validatePath']);
            $input->setArgument('path', $path);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('load-csv-command');
        $path = $input->getArgument('path');
        $data = $this->loadcsv->load($path);

        $this->io->text([
            $data,
        ]);

        $this->io->success(sprintf('Database was successfully created by File in path : %s',$path));

        $event = $stopwatch->stop('load-csv-command');
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp()
    {
        return <<<  'HELP'

The <info>%command.name%</info> command load csv file data and save it in the database:

  <info>php %command.full_name%</info> <comment>path</comment>

If you omit the required argument, the command will ask you to
provide the missing value:

  # command will ask you for the path
  <info>php %command.full_name%</info> <comment>path</comment>


HELP;
    }
}
