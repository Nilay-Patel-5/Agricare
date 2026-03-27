<?php

function ai_base_url(): string
{
    $port = getenv('AGRICARE_AI_PORT') ?: '5050';
    return "http://127.0.0.1:$port";
}

function ai_request(string $method, string $path, array $options = []): array
{
    $ch = curl_init(ai_base_url() . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $options['connect_timeout'] ?? 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout'] ?? 20);

    if (!empty($options['headers'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $options['headers']);
    }

    if (array_key_exists('body', $options)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options['body']);
    }

    $body = curl_exec($ch);
    $error = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    return [
        'ok' => $body !== false && $error === '',
        'status' => $status,
        'body' => $body === false ? '' : $body,
        'error' => $error,
    ];
}

function ai_is_healthy(): bool
{
    $response = ai_request('GET', '/health', [
        'connect_timeout' => 1,
        'timeout' => 2,
    ]);

    return $response['ok'] && $response['status'] === 200;
}

function ai_start_engine(): bool
{
    if (ai_is_healthy()) {
        return true;
    }

    $script = realpath(__DIR__ . '/../ai/start_predict_api.ps1');
    if ($script === false) {
        return false;
    }

    $stdoutLog = realpath(__DIR__ . '/../ai') . DIRECTORY_SEPARATOR . 'predict_api.out.log';
    $stderrLog = realpath(__DIR__ . '/../ai') . DIRECTORY_SEPARATOR . 'predict_api.err.log';
    $scriptArg = '"' . str_replace('"', '""', $script) . '"';
    $stdoutArg = '"' . str_replace('"', '""', $stdoutLog) . '"';
    $stderrArg = '"' . str_replace('"', '""', $stderrLog) . '"';
    $command = 'cmd /c start "" /B powershell -NoProfile -ExecutionPolicy Bypass -File '
        . $scriptArg
        . ' 1>>'
        . $stdoutArg
        . ' 2>>'
        . $stderrArg;
    @pclose(@popen($command, 'r'));

    for ($i = 0; $i < 4; $i++) {
        usleep(500000);
        if (ai_is_healthy()) {
            return true;
        }
    }

    return false;
}

function ai_ensure_engine(): bool
{
    return ai_is_healthy() || ai_start_engine();
}
