<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdvisorRepository extends EntityRepository
{
    
    public function getTotalRecodes(){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(a.id) as total
                  FROM AppBundle:Advisor a'
            )->getSingleScalarResult();
    }
}