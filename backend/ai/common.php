<?php

function ai_root_dir(): string
{
    return realpath(__DIR__ . '/../../ai') ?: (__DIR__ . '/../../ai');
}

function ai_project_root(): string
{
    return realpath(__DIR__ . '/../..') ?: (__DIR__ . '/../..');
}

function ai_model_path(): string
{
    return ai_root_dir() . DIRECTORY_SEPARATOR . 'plant_disease_model.keras';
}

function ai_predict_script_path(): string
{
    return ai_root_dir() . DIRECTORY_SEPARATOR . 'predict_cli.py';
}

function ai_python_candidates(): array
{
    $configured = trim((string) getenv('AGRICARE_AI_PYTHON'));
    $candidates = [];

    if ($configured !== '') {
        $candidates[] = preg_split('/\s+/', $configured);
    }

    $candidates[] = ['python'];
    $candidates[] = ['py', '-3'];
    $candidates[] = ['python3'];

    return $candidates;
}

function ai_command_label(array $command): string
{
    return implode(' ', $command);
}

function ai_run_process(array $command, string $cwd, int $timeoutSeconds = 30): array
{
    $descriptorSpec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $pipes = [];
    $process = @proc_open($command, $descriptorSpec, $pipes, $cwd, null, ['bypass_shell' => true]);
    if (!is_resource($process)) {
        return [
            'ok' => false,
            'exit_code' => 1,
            'stdout' => '',
            'stderr' => 'Failed to start process: ' . ai_command_label($command),
        ];
    }

    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $stdout = '';
    $stderr = '';
    $start = microtime(true);
    $finalStatus = null;

    while (true) {
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);

        $status = proc_get_status($process);
        $finalStatus = $status;
        if (!$status['running']) {
            break;
        }

        if ((microtime(true) - $start) > $timeoutSeconds) {
            proc_terminate($process);
            $stderr .= "\nProcess timed out after {$timeoutSeconds} seconds.";
            break;
        }

        usleep(100000);
    }

    $stdout .= stream_get_contents($pipes[1]);
    $stderr .= stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    $exitCode = proc_close($process);
    if (
        ($exitCode === -1 || $exitCode === 255)
        && is_array($finalStatus)
        && array_key_exists('exitcode', $finalStatus)
        && $finalStatus['exitcode'] !== -1
    ) {
        $exitCode = (int) $finalStatus['exitcode'];
    }

    return [
        'ok' => $exitCode === 0,
        'exit_code' => $exitCode,
        'stdout' => trim($stdout),
        'stderr' => trim($stderr),
    ];
}

function ai_find_python_command(): ?array
{
    static $resolved = null;

    if ($resolved !== null) {
        return $resolved;
    }

    foreach (ai_python_candidates() as $candidate) {
        $probe = ai_run_process(array_merge($candidate, ['--version']), ai_project_root(), 10);
        if ($probe['ok']) {
            $resolved = $candidate;
            return $resolved;
        }
    }

    return null;
}

function ai_is_healthy(): bool
{
    return file_exists(ai_model_path()) && file_exists(ai_predict_script_path()) && ai_find_python_command() !== null;
}

function ai_ensure_engine(): bool
{
    return ai_is_healthy();
}

function ai_run_prediction(string $imagePath, string $lang = 'en'): array
{
    $resolvedImagePath = realpath($imagePath);
    if (!$resolvedImagePath) {
        return ['error' => 'Image file not found: ' . $imagePath];
    }

    if (!file_exists(ai_model_path())) {
        return ['error' => 'Local AI model file is missing: ' . ai_model_path()];
    }

    if (!file_exists(ai_predict_script_path())) {
        return ['error' => 'Prediction script is missing: ' . ai_predict_script_path()];
    }

    $python = ai_find_python_command();
    if ($python === null) {
        return ['error' => 'Python runtime not found. Set AGRICARE_AI_PYTHON or install Python 3.'];
    }

    $result = ai_run_process(
        array_merge($python, [ai_predict_script_path(), '--image', $resolvedImagePath, '--lang', $lang]),
        ai_root_dir(),
        120
    );

    if (!$result['ok']) {
        return ['error' => trim($result['stderr'] !== '' ? $result['stderr'] : $result['stdout'])];
    }

    $data = json_decode($result['stdout'], true);
    if (!is_array($data)) {
        return ['error' => 'Invalid JSON returned by local AI inference.'];
    }

    return $data;
}
