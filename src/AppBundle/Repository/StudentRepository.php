<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StudentRepository extends EntityRepository
{
    public function findStudentsByGroups()
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT s.grade,count(s.grade) as groupnum
                  FROM AppBundle:Student s 
                  group by s.grade'
            )->getResult();
    }
    public function getTotalRecodes(){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(s.id) as total
                  FROM AppBundle:Student s'
            )->getSingleScalarResult();
    }
}