<?php

namespace App\Factory;

use App\Entity\Employee;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

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
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private readonly GoogleAuthenticatorInterface $googleAuthenticator) {
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
            'entry_date'                  => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeThisDecade()),
            'firstname'                   => self::faker()->firstName(),
            'email'                       => self::faker()->unique()->companyEmail(),
            'password'                    => 'password123',
            'googleAuthenticatorSecret'   => '',
            'roles'                       => self::faker()->randomElement([['ROLE_USER'], ['ROLE_ADMIN']]),
            'name'                        => self::faker()->lastName(),
            'status'                      => self::faker()->randomElement(['CDI', 'CDD', 'Freelance']),
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Employee $employee): void {
                // Hasher le mot de passe (on récupère $this->passwordHasher injecté dans le constructeur)
                $employee->setPassword(
                    $this->passwordHasher->hashPassword($employee, $employee->getPassword())
                );

                // Générer et fixer le secret 2FA en utilisant le service injecté
                $employee->setGoogleAuthenticatorSecret(
                    $this->googleAuthenticator->generateSecret()
                );
            });
    }
}
