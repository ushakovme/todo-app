<?php
declare(strict_types=1);

use App\Domain\Task\TaskRepositoryInterface;
use App\Infrastructure\Persistence\CycleORM\SQLTaskRepository;
use Cycle\ORM\ORMInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        TaskRepositoryInterface::class => function (ContainerInterface $container) {
            /* @var ORMInterface $orm */
            $orm = $container->get(ORMInterface::class);

            return new SQLTaskRepository(
                $orm
            );
        }
    ]);
};
