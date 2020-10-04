<?php


namespace App;


class MessageDetails
{
    public string $name;
    public string $email;
    public string $phone;
    public string $message;

    public function __construct($details)
    {
        $this->name = $details['name'] ?? 'Not provided';
        $this->email = $details['email'] ?? 'Not provided';
        $this->phone = $details['phone'] ?? 'Not provided';
        $this->message = $details['message'] ?? 'Not provided';
    }
}
