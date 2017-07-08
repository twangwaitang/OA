<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EvaluationDetailRepository extends EntityRepository
{
    public function getEvaluation($id) {
        $qb = $this->createQueryBuilder('e');
        $qb->select('avg(e.score) as score_avg,(e.user) as user')
           ->where('e.evaluation = :evaluation_id')
           ->setParameter('evaluation_id', $id)
           ->groupBy('e.user');
        $query = $qb->getQuery();
        return $query->execute();
    }
}