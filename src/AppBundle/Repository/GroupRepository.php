<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{

    public function findGroupsByTeacherId($id) {
        $q=$this->createQueryBuilder('g')
            ->innerJoin('g.teachers', 't')
            ->where('t.id = :teacher_id')
            ->setParameter('teacher_id', $id)
            ->getQuery()->getResult();
        return $q;
    }

    public function findUnrelatedGroups() {
        $query = 'SELECT p FROM AppBundle:Group p '.
            'WHERE NOT EXISTS ('.
            'SELECT c FROM AppBundle:Course c '.
            'WHERE p = c.group)';
        return $this->getEntityManager()
            ->createQuery($query)->getResult();

    }
}