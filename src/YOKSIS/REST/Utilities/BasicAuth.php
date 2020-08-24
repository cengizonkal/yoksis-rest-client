<?php

namespace Conkal\YOKSIS\REST\Utilities;


class BasicAuth implements AuthInterface
{

    private $username;
    private $password;


    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }


    public function toArray()
    {
        return
            [
                'auth' => [
                    $this->username,
                    $this->password
                ]
            ];
    }


}
