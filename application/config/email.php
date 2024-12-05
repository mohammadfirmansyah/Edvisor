<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp-relay.brevo.com';
$config['smtp_port']   = 587; // Port 587 untuk TLS
$config['smtp_user']   = '7ec98f001@smtp-brevo.com'; // SMTP Username Anda
$config['smtp_pass']   = '9CWpcESaZQ7F102O'; // SMTP Password Anda
$config['smtp_crypto'] = 'tls'; // Enkripsi yang digunakan: 'tls'
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['wordwrap']    = TRUE;
$config['newline']     = "\r\n";

// Email: edvisorfilkomub@gmail.com
// Password: edvisorfilkomub2024