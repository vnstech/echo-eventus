<?php

$json = [];

if (!$user) {
    $json = [
        'success' => false,
        'message' => 'User not found or invalid email.'
    ];
} else {
    $json = [
        'success' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ],
        'message' => 'User found successfully!'
    ];
}
