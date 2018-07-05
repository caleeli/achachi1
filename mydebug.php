:o<?php

$clients = array();
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'192.168.2.100', '9000');
socket_listen($socket);
socket_set_nonblock($socket);

while(true)
{
    if(($newc = socket_accept($socket)) !== false)
    {
        echo "Client $newc has connected\n";
        $clients[] = $newc;
    }
    foreach($clients as $cli) {
      $input = socket_read($cli, 1024);
      if($input) echo $input;
    }
}

die;

// Set time limit to indefinite execution

set_time_limit (0);

// Set the ip and port we will listen on

$address = '192.168.2.100';

$port = 9000;

$max_clients = 10;

// Array that will hold client information

$clients = Array();

// Create a TCP Stream socket

$sock = socket_create(AF_INET, SOCK_STREAM, 0);

// Bind the socket to an address/port

socket_bind($sock, $address, $port) or die('Could not bind to address');

// Start listening for connections

socket_listen($sock);

// Loop continuously

while (true) {

    // Setup clients listen socket for reading

    $read[0] = $sock;

    for ($i = 0; $i < $max_clients; $i++)

    {

        if ($client[$i]['sock']  != null)

            $read[$i + 1] = $client[$i]['sock'] ;

    }

    // Set up a blocking call to socket_select()

    $ready = socket_select($read,$WRITE,$EXCEPT,null);

    /* if a new connection is being made add it to the client array */

    if (in_array($sock, $read)) {

        for ($i = 0; $i < $max_clients; $i++)

        {

            if ($client[$i]['sock'] == null) {

                $client[$i]['sock'] = socket_accept($sock);

                break;

            }

            elseif ($i == $max_clients - 1)

                print ("too many clients");

        }

        if (--$ready <= 0)

            continue;

    } // end if in_array

    

    // If a client is trying to write - handle it now

    for ($i = 0; $i < $max_clients; $i++) // for each client

    {

        if (in_array($client[$i]['sock'] , $read))

        {

            $input = socket_read($client[$i]['sock'] , 1024);

            if ($input == null) {

                // Zero length string meaning disconnected

                unset($client[$i]);

            }

            $n = trim($input);

            if ($input == 'exit') {

                // requested disconnect

                socket_close($client[$i]['sock']);

            } elseif ($input) {
              PRINT $input;

                // strip white spaces and write back to user

                $output = ereg_replace("[ \t\n\r]","",$input).chr(0);

                socket_write($client[$i]['sock'],$output);

            }

        } else {

            // Close the socket

            socket_close($client[$i]['sock']);

            unset($client[$i]);

        }

    }

} // end while

// Close the master sockets

socket_close($sock);

?>