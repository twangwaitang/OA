<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TeacherRepository extends EntityRepository
{

    public function getTotalRecodes(){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(t.id) as total
                  FROM AppBundle:Teacher t'
            )->getSingleScalarResult();
    }


}