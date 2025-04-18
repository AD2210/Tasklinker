<?php

namespace App\Factory;

use App\Entity\Employee;
use App\Entity\Project;
use App\Repository\EmployeeRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Project>
 */
final class ProjectFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * 
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Project::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * 
     */
    protected function defaults(): array|callable
    {
        return [
            'archived' => self::faker()->boolean(),
            'name' => self::faker()->sentence(5),
            'employees' =>self::faker()->randomElements(EmployeeFactory::all(),random_int(0,4))
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }
}
