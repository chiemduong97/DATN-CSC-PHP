<?php

/**
 * Socket The server
 */
    class SocketServer
    {
        private $sockets; //Connection pool
        private $master;
        private $handshake;

        /**
         * @param $address
         * @param $port
         */
        public function run($address, $port)
        {
            //Configure error level, run time, refresh buffer
            echo iconv('UTF-8', 'GBK', "Welcome to PHP Socket Test services. \n");
            error_reporting(0);
            set_time_limit(0);
            ob_implicit_flush();

            //Establish socket
            $this->master = $this->_connect($address, $port);
            $this->sockets[] = $this->master;

            //Function socket
            while (true) {
                $sockets = $this->sockets;
                $write = NULL;
                $except = NULL;
                socket_select($sockets, $write, $except, NULL); //$write,$except Citation
                foreach ($sockets as $socket) {
                    if ($socket == $this->master) {
                        $client = socket_accept($socket);
                        $this->handshake = false;
                        if ($client) {
                            $this->sockets[] = $client; //Join connection pool
                        }
                    } else {
                        //Receiving information
                        $bytes = @socket_recv($socket, $buffer, 2048, 0);
                        if ($bytes <= 6) {
                            $this->_disConnect($socket);
                            continue;
                        };

                        //process information
                        if (!$this->handshake) {
                            $this->_handshake($socket, $buffer);
                        } else {
                            $buffer = $this->_decode($buffer);
                            $this->_sendMsg($buffer, $socket);
                        }
                    }

                }
            }
        }

        /**
         * Create socket connection
         * @param $address
         * @param $port
         * @return resource
         */
        private function _connect($address, $port)
        {
            //Establish socket
            $master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)
            or die("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
            socket_bind($master, $address, $port)
            or die("socket_bind() failed: reason: " . socket_strerror(socket_last_error($master)) . "\n");
            socket_listen($master, 5)
            or die("socket_listen() failed: reason: " . socket_strerror(socket_last_error($master)) . "\n");
            return $master;
        }

        /**
         * Handshake
         * @param $socket
         * @param $buffer
         */
        private function _handshake($socket, $buffer)
        {
            //Handshake action information
            $buf = substr($buffer, strpos($buffer, 'Sec-WebSocket-Key:') + 18);
            $key = trim(substr($buf, 0, strpos($buf, "\r\n")));
            $new_key = base64_encode(sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
            $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
            $new_message .= "Upgrade: websocket\r\n";
            $new_message .= "Sec-WebSocket-Version: 13\r\n";
            $new_message .= "Connection: Upgrade\r\n";
            $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";

            //Record handshake
            socket_write($socket, $new_message, strlen($new_message));
            $this->handshake = true;
        }

        /**
         * Disconnect socket
         * @param $socket
         */
        private function _disConnect($socket)
        {
            $index = array_search($socket, $this->sockets);
            socket_close($socket);
            if ($index >= 0) {
                array_splice($this->sockets, $index, 1);
            }
        }

        /**
         * Send message
         * @param $buffer
         * @param $client
         */
        private function _sendMsg($buffer, $client)
        {
            $send_buffer = $this->_frame(json_encode($buffer));
            // multithread, tao 1 luong de luu tin nhan
            foreach ($this->sockets as $socket) {
                if ($socket != $this->master && $socket != $client) { //Broadcast and send (except yourself)
                    socket_write($socket, $send_buffer, strlen($send_buffer));
                }
            }
        }

        /**
         * Parse data frame
         * @param $buffer
         * @return null|string
         */
        private function _decode($buffer)
        {
            $len = $masks = $data = $decoded = null;
            $len = ord($buffer[1]) & 127;
            if ($len === 126) {
                $masks = substr($buffer, 4, 4);
                $data = substr($buffer, 8);
            } else if ($len === 127) {
                $masks = substr($buffer, 10, 4);
                $data = substr($buffer, 14);
            } else {
                $masks = substr($buffer, 2, 4);
                $data = substr($buffer, 6);
            }
            for ($index = 0; $index < strlen($data); $index++) {
                $decoded .= $data[$index] ^ $masks[$index % 4];
            }
            return $decoded;
        }

        /**
         * Process return frame
         * @param $buffer
         * @return string
         */
        private function _frame($buffer)
        {
            $len = strlen($buffer);
            if ($len <= 125) {
                return "\x81" . chr($len) . $buffer;
            } else if ($len <= 65535) {
                return "\x81" . chr(126) . pack("n", $len) . $buffer;
            } else {
                return "\x81" . chr(127) . pack("xxxxN", $len) . $buffer;
            }
        }
    }

    $sc = new SocketServer();
    $sc->run('192.168.1.3', 8686);

?>