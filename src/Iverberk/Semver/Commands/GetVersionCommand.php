<?php namespace Iverberk\Semver\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetVersionCommand extends Command {

	protected function configure()
	{
		$this
			->setName('get')
			->setDescription('Get current semantic version number from a JSON configuration file')
			->addArgument(
				'file',
				InputArgument::REQUIRED,
				'JSON configuration file'
			)
			->addOption(
				'key',
				null,
				InputOption::VALUE_OPTIONAL,
				'JSON key that contains the version number',
				'version'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$file = $input->getArgument('file');
		$config = json_decode(file_get_contents($file), true);

		if (json_last_error() == JSON_ERROR_NONE)
		{
			$key = $input->getOption('key');
			if (isset($config[$key]))
			{
				$output->writeln($config[$key]);
			}
		}
		else
		{
			$output->write('<error>Could not decode JSON file</error>', true);
		}
	}

} 