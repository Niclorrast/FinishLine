<?php
//Metodo para enviar correo parametrizado
function enviarCorreo(string $destinatario, string $asunto, string $cuerpoHtml): bool {
    $apiKey = getenv('BREVO_API_KEY') ?: ($_ENV['BREVO_API_KEY'] ?? null);
    if (empty($apiKey)) {
        error_log('Brevo SMTP: API key missing');
        return false;
    }

    $datos = [
        'sender'     => ['name' => 'FinishLine', 'email' => 'finishlineheesni@gmail.com'],
        'to'         => [['email' => $destinatario]],
        'subject'    => $asunto,
        'htmlContent'=> $cuerpoHtml
    ];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-key: ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

    $respuesta = curl_exec($ch);
    $curlErr   = curl_error($ch);
    $codigo    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log(sprintf('Brevo SMTP: to=%s code=%s curl_err=%s response=%s', $destinatario, $codigo, $curlErr, $respuesta));

    return $codigo === 201;
}

//metodo para debug, devuelve detalles de la peticion *NO ALTERA*
function enviarCorreoDebug(string $destinatario, string $asunto, string $cuerpoHtml): array {
    $apiKey = getenv('BREVO_API_KEY') ?: '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';

    $datos = [
        'sender'     => ['name' => 'FinishLine', 'email' => 'finishlineheesni@gmail.com'],
        'to'         => [['email' => $destinatario]],
        'subject'    => $asunto,
        'htmlContent'=> $cuerpoHtml
    ];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-key: ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

    $respuesta = curl_exec($ch);
    $curlErr   = curl_error($ch);
    $codigo    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'ok' => $codigo === 201,
        'http_code' => $codigo,
        'curl_error' => $curlErr,
        'response' => $respuesta,
        'payload' => $datos,
    ];
}