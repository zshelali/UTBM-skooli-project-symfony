<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection; //connect to the db
use Doctrine\DBAL\Exception; //asked by phpstorm
use App\Entity\User;
use App\Entity\Role;

class AppFixtures extends Fixture
{



    public function load(ObjectManager $manager): void
    {
        $conn = $manager->getConnection();

        $stmt1 = $conn->prepare('TRUNCATE TABLE role RESTART IDENTITY CASCADE');
        $stmt1->executeStatement();
        $stmt2 = $conn->prepare('ALTER SEQUENCE role_id_seq RESTART WITH 1');
        $stmt2->executeStatement();

        // default roles
        $roles = ['admin', 'student', 'professor', 'profadmin'];
        foreach ($roles as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $manager->persist($role);
        }
        $manager->flush();

        // initial user
        $user = new User();
        $user->setFirstName('Ad');
        $user->setLastName('Min');
        $user->setEmail('admin@skooli.com');
        $user->setProfilePicture('img/avatar.png');
        $user->setPassword(password_hash('admin', PASSWORD_DEFAULT));
        $roleRepository = $manager->getRepository(Role::class);
        $userRole = $roleRepository->findOneBy(['name' => 'admin']);
        $user->setIdRole($userRole);
        $manager->persist($user);

        $manager->flush();
    }
}
