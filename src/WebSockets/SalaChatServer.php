<?php

use App\WebSockets\WebSocketServer;

class SalaChatServer extends WebSocketServer
{
    // Called immediately when the data is recieved.
    public function process($user, $message)
    {
        echo 'user sent: ' . $message . PHP_EOL;
        foreach ($this->users as $currentUser) {
            if ($currentUser !== $user) {
                $this->send($currentUser, $message);
            }
        }
    }
    // Called after the handshake response is sent to the client.
    public function connected($user)
    {
        echo 'user connected' . PHP_EOL;
    }
    // Called after the connection is closed.
    public function closed($user)
    {
        echo 'user disconnected' . PHP_EOL;
    }
}


$chatServer = new SalaChatServer("localhost", "9000");
try {
    $chatServer->run();
} catch (Exception $e) {
    $chatServer->stdout($e->getMessage());
}
