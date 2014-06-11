<?php
/**
 * console application for portfolio
 * @copyrights 2014 mparaiso <mparaiso@online.fr>
 * @license all rights reserved
 */
use Entity\User;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

$autoload = require(__DIR__ . '/../vendor/autoload.php');
$autoload->add('', __DIR__ . '/../app');

$app = new App(array('debug' => true, 'session.test' => true));

$app->boot();
/* @var Application $console */
$console = $app['console'];

/** register users */
$console->register('user:register')
    ->addOption('username', 'u', InputOption::VALUE_REQUIRED, "username")
    ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'password')
    ->addOption('email', 'e', InputOption::VALUE_REQUIRED, 'email')
    ->setDescription("Register a new user")
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $user = new User;
        $user->setUsername($input->getOption('username'));
        $user->setPassword($input->getOption('password'));
        $user->setEmail($input->getOption('email'));
        $errors = $app->validator->validate($user, array('registration'));
        if (count($errors) > 0) {
            $output->write((string)$errors);
        } else {
            $app->userService->register($user);
            $output->writeln("user $user successfully registered !");
        }
    });

$console->run();