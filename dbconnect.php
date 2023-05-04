<?php

$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS YouChat";

if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

$sql = "USE YouChat";

if (!mysqli_query($conn, $sql)) {
    die("Unable to use database: " . mysqli_error($conn));
}

$sql = "CREATE TABLE IF NOT EXISTS users(
    username VARCHAR(63),
    fullname VARCHAR(63),
    email VARCHAR(63),
    password VARCHAR(255),
    about VARCHAR(255),
    profile_picture MEDIUMBLOB,
    last_online TIMESTAMP,
    connection_id VARCHAR(255),
    PRIMARY KEY (username)
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating table: " . mysqli_error($conn));
}

$sql = "CREATE TABLE IF NOT EXISTS chats(
    uname VARCHAR(63), 
    cname VARCHAR(63),
    last_message VARCHAR(255),
    last_message_time TIMESTAMP NULL,
    disappearing_mode BOOLEAN,
    PRIMARY KEY(uname, cname),
    FOREIGN KEY (uname) REFERENCES users(username),
    FOREIGN KEY (cname) REFERENCES users(username)    
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating table: " . mysqli_error($conn));
}

$sql = "CREATE TABLE IF NOT EXISTS messages(
    id INT AUTO_INCREMENT,
    uname VARCHAR(63), 
    cname VARCHAR(63),
    content VARCHAR(255),
    media MEDIUMBLOB,
    created_at TIMESTAMP,
    disappearing BOOLEAN,
    PRIMARY KEY (id),
    FOREIGN KEY (uname) REFERENCES users(username),
    FOREIGN KEY (cname) REFERENCES users(username) 
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating table: " . mysqli_error($conn));
}

?>