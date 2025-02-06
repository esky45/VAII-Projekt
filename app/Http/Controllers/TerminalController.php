<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TerminalController extends Controller
{
    public function index()
    {
        return view('tester.terminal');
    }

    public function execute(Request $request)
    {
        $command = $request->input('command');

        // Basic validation to prevent potentially harmful commands
        $allowedCommands = [
            'ls',
            'pwd',
            'cd',
            'php artisan migrate',
            'php artisan route:list', 
            
        ];

        // Validate the command
        $commandParts = explode(' ', $command);
        $baseCommand = $commandParts[0];
/*
        if (!in_array($baseCommand, $allowedCommands)) {
            return response()->json(['error' => 'Command not allowed!'], 403);
        }
*/
        // Adjust for Windows or Unix-based systems
        if (strtolower(substr(php_uname(), 0, 3)) === 'win') {
            // For Windows, replace `ls` with `dir` and `pwd` with `cd`
            if ($baseCommand == 'ls') {
                $command = 'dir';
            } elseif ($baseCommand == 'pwd') {
                $command = 'cd';
            }
        }

        // Execute the command securely
        try {
             $process = new Process(explode(' ', $command));
            $process->setWorkingDirectory(base_path('/int')); // Ensure commands are run in the Laravel root directory
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Convert output to UTF-8 
            $output = $process->getOutput();

         //detect the encoding and convert it
            $detectedEncoding = mb_detect_encoding($output, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
            if ($detectedEncoding) {
                $output = mb_convert_encoding($output, 'UTF-8', $detectedEncoding);
            } else {
                $output = mb_convert_encoding($output, 'UTF-8', 'auto');
            }

            return response()->json(['output' => nl2br($output)]);
        } catch (ProcessFailedException $e) {
            return response()->json(['error' => 'Command execution failed: ' . $e->getMessage()]);
        }
    }
}
