<?php

// Hello World! SSL HTTP Server.
// Tested on PHP 5.1.2-1+b1 (cli) (built: Mar 20 2006 04:17:24)

// Certificate data:
$dn = [
    'countryName' => 'UK',
    'stateOrProvinceName' => 'Somerset',
    'localityName' => 'Glastonbury',
    'organizationName' => 'The Brain Room Limited',
    'organizationalUnitName' => 'PHP Documentation Team',
    'commonName' => 'Wez Furlong',
    'emailAddress' => 'wez@example.com',
];

// Generate certificate
$privkey = openssl_pkey_new();
$cert = openssl_csr_new($dn, $privkey);
$cert = openssl_csr_sign($cert, null, $privkey, 365);

// Generate PEM file
// Optionally change the passphrase from 'comet' to whatever you want, or leave it empty for no passphrase
$pem_passphrase = 'comet';
$pem = [];
openssl_x509_export($cert, $pem[0]);
openssl_pkey_export($privkey, $pem[1], $pem_passphrase);
$pem = implode($pem);

// Save PEM file
$pemfile = './server.pem';
file_put_contents($pemfile, $pem);

$context = stream_context_create();

// local_cert must be in PEM format
stream_context_set_option($context, 'ssl', 'local_cert', $pemfile);
// Pass Phrase (password) of private key
stream_context_set_option($context, 'ssl', 'passphrase', $pem_passphrase);

stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
stream_context_set_option($context, 'ssl', 'verify_peer', false);

// Create the server socket
$server = stream_socket_server('ssl://0.0.0.0:8081', $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $context);

if (!$server) {
    die("$errstr ($errno)" . PHP_EOL);
}

declare(ticks=1);
// pcntl_async_signals(true);                                    // Allow posix signal handling

function shutdown()
{
    // echo "\033c";                                        // Clear terminal
    // system("tput cnorm && tput cup 0 0 && stty echo");   // Restore cursor default
    echo PHP_EOL;
    global $server;                                  // New line
    stream_socket_shutdown($server, STREAM_SHUT_WR); // (deshabilita recepciones y transmisiones).
    exit;                                                // Clean quit
}
pcntl_signal(SIGINT, 'shutdown');                         // Catch SIGINT, run shutdown()

while (true) {

    // pcntl_signal_dispatch();
    $buffer = '';
    echo 'waiting...';
    $client = stream_socket_accept($server, 36000);
    if ($client) {
        echo 'accepted ' . stream_socket_get_name($client, true) . "\n";
        // Read until double CRLF
        while (!preg_match('/\r?\n\r?\n/', $buffer)) {
            $buffer .= fread($client, 2046);
        }
        // Respond to client
        fwrite($client, "200 OK HTTP/1.1\r\n"
            . "Connection: close\r\n"
            . "Content-Type: text/html\r\n"
            . "\r\n"
            . 'Hello World! ' . microtime(true)
            . "<pre>{$buffer}</pre>");
        fclose($client);
    } else {
        echo "error.\n";
    }
}
