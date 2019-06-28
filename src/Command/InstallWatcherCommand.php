<?php

namespace MIA3\CodingStandard\Command;

use App\Security\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

class InstallWatcherCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('install:watcher')
            ->setDescription('install watcher into .idea/watcherTasks.xml')
            ->setHelp(
                <<<'EOT'
...
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        file_put_contents(
            '.idea/watcherTasks.xml',
            '<?xml version="1.0" encoding="UTF-8"?>
<project version="4">
  <component name="ProjectTasksOptions">
    <TaskOptions isEnabled="true">
      <option name="arguments" value="fix $FilePathRelativeToProjectRoot$" />
      <option name="checkSyntaxErrors" value="true" />
      <option name="description" />
      <option name="exitCodeBehavior" value="ERROR" />
      <option name="fileExtension" value="*" />
      <option name="immediateSync" value="false" />
      <option name="name" value="mia3/coding-standard" />
      <option name="output" value="$FilePath$" />
      <option name="outputFilters">
        <array />
      </option>
      <option name="outputFromStdout" value="false" />
      <option name="program" value="$USER_HOME$/.composer/vendor/mia3/coding-standard/bin/mia3-coding-standard" />
      <option name="runOnExternalChanges" value="true" />
      <option name="scopeName" value="All Places" />
      <option name="trackOnlyRoot" value="false" />
      <option name="workingDir" value="$ProjectFileDir$" />
      <envs />
    </TaskOptions>
  </component>
</project>
'
        );
    }
}
