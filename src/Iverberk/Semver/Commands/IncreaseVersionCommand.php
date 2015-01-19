<?php namespace Iverberk\Semver\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Naneau\SemVer\Parser;

class IncreaseVersionCommand extends Command {

	protected function configure()
	{
		$this
			->setName('increase')
			->setDescription('Increase the current semantic version number from a JSON configuration file with specified type')
			->addArgument(
				'file',
				InputArgument::REQUIRED,
				'JSON configuration file'
			)
			->addOption(
				'type',
				null,
				InputOption::VALUE_OPTIONAL,
				'Release type (major, minor, patch or build)',
				'patch'
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

		$type = $input->getOption('type');
		if ( ! in_array($type, ['major', 'minor', 'patch', 'build']))
		{
			return $this->error($output, 'Type can only be one of major, minor, patch or build');
		}

		if (json_last_error() == JSON_ERROR_NONE)
		{
			$key = $input->getOption('key');
			if (isset($config[$key]))
			{
				$version = Parser::parse($config[$key]);

				$version->setPatch($version->getPatch() + 1);

				$config[$key] = $version;

				file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
			}
		}
		else
		{
			return $this->error($output, 'Could not decode JSON file');
		}

		$output->writeln('New version: ' . $config[$key]);

		return 0;
	}

	/**
	 * @param OutputInterface $output
	 * @param string $msg
	 * @return int
	 */
	private function error(OutputInterface $output, $msg)
	{
		$output->write("<error>$msg</error>", true);

		return 1;
	}

}