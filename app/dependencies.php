<?php
declare(strict_types=1);

use Cycle\ORM\Factory;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\SchemaInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Spiral\Database\Config\DatabaseConfig;
use Spiral\Database\DatabaseManager;
use Spiral\Database\DatabaseProviderInterface;
use Spiral\Database\Driver\MySQL\MySQLDriver;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        DatabaseProviderInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $dbSettings = $settings['db'];

            return new DatabaseManager(
                new DatabaseConfig([
                    'default' => 'default',
                    'databases' => [
                        'default' => ['connection' => 'mysql']
                    ],
                    'connections' => [
                        'mysql' => [
                            'driver' => MySQLDriver::class,
                            'connection' => $dbSettings['connection'],
                            'username' => $dbSettings['username'],
                            'password' => $dbSettings['password'],
                        ]
                    ]
                ])
            );
        },

        ORMInterface::class => function (ContainerInterface $container) {
            return new Cycle\ORM\ORM(
                new Factory(
                    $container->get(DatabaseProviderInterface::class)
                ),
                $container->get(SchemaInterface::class)
            );
        },
    ]);
};
