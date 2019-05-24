<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getRandomClient()
    {
        $users = $this->createQueryBuilder('u')
                      ->andWhere('u.type = :type')
                      ->setParameter('type', UserType::Client)
                      ->getQuery()
                      ->getResult();
        $user = $users[rand(0, count($users)-1)];
        return $user;
    }

    public function findOrFail($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
            
        $user = $this->createQueryBuilder('u')
                ->andWhere('u.id = :id')
                ->setParameter('id', $value)
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }

        return $user;
    }


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
