<?php

namespace App\Factory;

use App\Entity\Employee;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Employee>
 */
final class EmployeeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * 
     */
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {
        parent::__construct();
    }

    public static function class(): string
    {
        return Employee::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * 
     */
    protected function defaults(): array|callable
    {
        return [
            'entry_date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeThisDecade()),
            'firstname' => self::faker()->firstName(),
            'email' => self::faker()->unique()->companyEmail(),
            'password' => 'password123',
            'roles' => self::faker()->randomElement([['ROLE_USER'], ['ROLE_ADMIN']]),
            'name' => self::faker()->lastName(),
            'status' => self::faker()->randomElement(['CDI', 'CDD', 'Freelance']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Employee $employee): void {
                $employee->setPassword($this->passwordHasher->hashPassword($employee, $employee->getPassword()));
            })
        ;
    }
}
