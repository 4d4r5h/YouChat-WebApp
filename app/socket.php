<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        // $this->clients = new \SplObjectStorage;
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $connection)
    {
        // Store the new connection to send messages to later
        // $this->clients->attach($connection);
        $this->clients[$connection->resourceId] = [ 'connection' => $connection, 'username' => "69" ];

        echo "New connection! ({$connection->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // $numRecv = count($this->clients) - 1;
        // echo sprintf(
        //     'Connection %d sending message "%s" to %d other connection%s' . "\n",
        //     $from->resourceId,
        //     $msg,
        //     $numRecv,
        //     $numRecv == 1 ? '' : 's'
        // );

        // foreach ($this->clients as $client) {
        //     if ($from !== $client) {
        //         // The sender is not the receiver, send to each client connected
        //         $client->send($msg);
        //     }
        // }
        // echo $msg;

        $json = json_decode($msg);

        $userName = $json->{"username"};
        $type = $json->{"type"};

        include 'dbconnect.php';

        if($type == 0) {
            $connection_id = $from->resourceId;
        $sql = "UPDATE users SET connection_id = '$connection_id' WHERE username='" . $userName . "'";
        // echo $msg;
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                die("Error in updating connection ID: " . mysqli_error($conn));
            }
            else
            {
                $this->clients[$from->resourceId]["username"] = $userName;
                // echo $this->clients[$from->resourceId]['username'];
            }
        }
        else
        {

            $message = $json->{"message"};

            $from_username = $this->clients[$from->resourceId]["username"];

            // echo $from_username . " " . $userName;

            $sql = "INSERT INTO messages
            (uname, cname, content, disappearing)
            VALUES ('$from_username', '$userName', '$message', false);";
        $result = mysqli_query($conn, $sql);
        if (!$result) { 
            // echo "<script>document.getElementById('alert').style.display='block';</script>";
            die("Error inserting into messages table: " . mysqli_error($conn));
        }

        // $sql = "INSERT INTO messages
        //     (uname, cname, content, disappearing)
        //     VALUES ('$userName', '$from_username', '$message', false);";
        // $result = mysqli_query($conn, $sql);
        // if (!$result) { 
        //     // echo "<script>document.getElementById('alert').style.display='block';</script>";
        //     die("Error inserting into messages table: " . mysqli_error($conn));
        // }

            $sql = "SELECT * from users WHERE username = '" . $userName . "'";
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($result))
            {
                $to_send->message = $message;
                $to_send->from = $from_username;
                $json_to_send = json_encode($to_send);
                $this->clients[$row["connection_id"]]["connection"]->send($json_to_send);
            }
        }
        

    }

    public function onClose(ConnectionInterface $connection)
    {

        include 'dbconnect.php';
        $username = $this->clients[$connection->resourceId]['username'];

        $sql = "UPDATE users SET connection_id = NULL WHERE username=" . "'" . $username . "'";
        // echo $msg;
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                die("Error in updating connection ID: " . mysqli_error($conn));
            }

        // The connection is closed, remove it, as we can no longer send it messages
        // $this->clients->detach($connection);
        unset($this->clients[$connection->resourceId]);

        echo "Connection {$connection->resourceId} has disconnected\n";

    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $connection->close();
    }
}

?>
