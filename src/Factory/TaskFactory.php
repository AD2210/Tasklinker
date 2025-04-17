<?php

namespace App\Factory;

use App\Entity\Task;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * 
     */
    public function __construct() {}

    public static function class(): string
    {
        return Task::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * 
     */
    protected function defaults(): array|callable
    {
        return [
            'status' => self::faker()->randomElement(['To Do', 'Doing', 'Done']),
            'title' => self::faker()->sentence(3),
            'project' => self::faker()->randomElement([ProjectFactory::all()]),
            'employee' => self::faker()->randomElement([EmployeeFactory::findBy(['projects' => self::last('project')])])
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
