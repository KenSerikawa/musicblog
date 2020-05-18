<?php

namespace App\Controller;

use Symfony\Bridge\Monolog\Handler\SwiftMailerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;

class MailerController extends AbstractController
{

}
