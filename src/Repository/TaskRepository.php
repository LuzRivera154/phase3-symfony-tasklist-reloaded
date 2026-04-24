<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findTaskByPinned(): array
    {
        return $this->createQueryBuilder("t")
            ->orderBy('t.isPinned', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findTasksByFilters(
        User $user,
        ?int $folderId = null,
        ?string $priorityName = null,
        ?string $statusName = null
    ): array {
        $qb = $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user);

        // Filtro por Carpeta
        if ($folderId) {
            $qb->andWhere('t.Folder = :folderId')
                ->setParameter('folderId', $folderId);
        }

        // Filtro por Status 
        if ($statusName && $statusName !== '') {
            $qb->andWhere('t.status = :status')
                ->setParameter('status', $statusName);
        }

        // Filtro por Prioridad
        if ($priorityName && $priorityName !== '') {
            $qb->leftJoin('t.priority', 'p')
                ->andWhere('p.name = :pName')
                ->andWhere($qb->expr()->orX(
                    'p.user IS NULL',
                    'p.user = :user'
                ))
                ->setParameter('pName', $priorityName);
        }

        // Orden
        return $qb->orderBy('t.isPinned', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
