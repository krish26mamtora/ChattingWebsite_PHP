<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $temp ='';
   

    public function __construct()
    {
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;
        echo "New connection! ({$conn->resourceId})\n";
    }
    
    public function onMessage(ConnectionInterface $from, $msg)
    {
      
        $data = json_decode($msg, true);

        if (isset($data['type'])) {
            if ($data['type'] == 'register' && isset($data['userId'])) {
                $this->temp =$data['receiver'];
                $this->clients[$from->resourceId]->userId = $data['userId'];     // for client/receiver uid of receiver 
                $this->clients[$from->resourceId]->username = $data['username'];
                $this->clients[$from->resourceId]->sendto = $data['receiver']; // for client/receiver uid of sender
                echo "User {$data['userId']} ({$data['username']}) registered  sending to {$data['receiver']}  .\n";
                
            } elseif ($data['type'] == 'message' && isset($data['name'], $data['msg'], $data['sender'], $data['receiver'])) {
                $name = $data['name'];
                $msg = $data['msg'];
                $sender = $data['sender'];  //id of sender at sender side -- at receiver side compare it with id of his message receiver       || clent->
                $receiver = $data['receiver']; ///id of message receiver at sender side -- compare with id of his message sender at receiver side || client->

                foreach ($this->clients as $client) {
                    if (isset($client->userId) && ($client->userId == $receiver) &&($client->sendto == $sender)) {
                    
                        echo "for this message sender is : {$sender} and receiver : {$receiver} .\n";
                        echo "new changed user sending to : {$this->temp} old sender is: {$sender}.\n" ;
                        echo " {$receiver} this old and {$this->temp} are same or not.\n";

                        echo "\n"; 
                        if( ($this->temp == $sender) || ($this->temp ==$receiver)){
                            echo "Same User.\n";
                            $client->send(json_encode([
                                'name' => $name,
                                'msg' => $msg,
                                'sender' => $sender,
                                'receiver' => $receiver
                            ]));
                        }else{
                            echo "Different users .\n";
                          
                        }
                        
                    }}
            
            }
        } else {
            echo "Invalid message format received\n";
        }
    }


    public function onClose(ConnectionInterface $conn)
    {
        unset($this->clients[$conn->resourceId]);
      
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
