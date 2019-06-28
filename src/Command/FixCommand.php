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

class FixCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fix')
            ->setDescription('fix code')
            ->setDefinition(
                [
                    new InputArgument('path', InputArgument::REQUIRED, 'The File/Directory to format'),
                ]
            )
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
        $path = $input->getArgument('path');

        if (!file_exists($path)) {
            throw new \Exception($path . ' does not exist!');
        }

        if (is_file($path)) {
            $file = new \SplFileInfo($path);
            switch ($file->getExtension()) {
                case 'sass':
                case 'scss':
                case 'less':
                case 'css':
                    $process = new Process(
                        [
                            __DIR__ . '/../../node_modules/.bin/stylelint',
                            getcwd() . '/' . $path,
                            '--fix',
                            '--config',
                            __DIR__ . '/../../config/stylelint.json',
                        ]
                    );
                    break;
                case 'php':
                    $process = new Process(
                        [
                            __DIR__ . '/../../vendor/bin/ecs',
                            'check',
                            getcwd() . '/' . $path,
                            '--fix',
                            '--config',
                            __DIR__ . '/../../config/easy-coding-standard.yaml',
                        ]
                    );
                    break;
                case 'yaml':
                case 'yml':
                case 'json':
                case 'vue':
                    $process = new Process(
                        [
                            __DIR__ . '/../../node_modules/.bin/prettier',
                            getcwd() . '/' . $path,
                            '--write',
                            '--config',
                            __DIR__ . '/../../config/prettier.config.js',
                        ]
                    );
                    break;
                case 'html':
                    $process = new Process(
                        [
                            __DIR__ . '/../../node_modules/.bin/unibeautify',
                            '--language',
                            'HTML',
                            '--file-path',
                            getcwd() . '/' . $path,
                            '--inplace',
                            '--config-file',
                            __DIR__ . '/../../config/unibeautifyrc.json',
                        ]
                    );
                    break;
                case 'twig':
                    $process = new Process(
                        [
                            __DIR__ . '/../../node_modules/.bin/unibeautify',
                            '--language',
                            'Twig',
                            '--file-path',
                            getcwd() . '/' . $path,
                            '--inplace',
                            '--config-file',
                            __DIR__ . '/../../config/unibeautifyrc.json',
                        ]
                    );
                    break;
                case 'js':
                    $process = new Process(
                        [
                            __DIR__ . '/../../node_modules/.bin/standard',
                            getcwd() . '/' . $path,
                            '--fix',
                            '--verbose',
                        ]
                    );
                    break;
                // default:
                //     throw new \Exception('unhandled extension: ' . $file->getExtension());
            }
            if ($process instanceof Process) {
                $process->run(
                    function ($type, $buffer) use ($output) {
                        $output->write($buffer);
                    }
                );
                exit($process->getExitCode());
            }
        } else {
            // $process = new Process(
            //     [
            //         __DIR__ . '/../../node_modules/.bin/stylelint',
            //         rtrim($path, '/') . '/**/*.(scss|sass|less|css)',
            //         '--fix',
            //         '--config',
            //         __DIR__ . '/../../config/stylelint.json',
            //     ]
            // );
            // $process->run(
            //     function ($type, $buffer) use ($output) {
            //         $output->write($buffer);
            //     }
            // );
            //
            // $process = new Process(
            //     [
            //         __DIR__ . '/../../vendor/bin/ecs',
            //         'check',
            //         $path,
            //         '--fix',
            //         '--config',
            //         __DIR__ . '/../../config/easy-coding-standard.yaml',
            //     ]
            // );
            // $process->run(
            //     function ($type, $buffer) use ($output) {
            //         $output->write($buffer);
            //     }
            // );
            //
            // $process = new Process(
            //     [
            //         __DIR__ . '/../../node_modules/.bin/standard',
            //         rtrim($path, '/') . '/**/*.js',
            //         '--fix',
            //         '--verbose',
            //     ]
            // );
            // $process->run(
            //     function ($type, $buffer) use ($output) {
            //         $output->write($buffer);
            //     }
            // );
        }
    }
}
