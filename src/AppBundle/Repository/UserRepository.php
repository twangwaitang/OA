<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllWithPosition()
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT u.id,u.username,u.age,u.email,u.createdTime,p.name as position
                  FROM AppBundle:User u 
                  left join AppBundle:Position p 
                  where u.pid = p.id'
            )->getResult();
    }
    public function findUsersByGroups()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u.roles,count(u.roles) as groupnum
                  FROM AppBundle:User u 
                  group by u.roles'
            )->getResult();
    }
    public function getTotalRecodes(){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(u.id) as total
                  FROM AppBundle:User u'
            )->getSingleScalarResult();
    }

    public function findByRole($role)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u 
                 WHERE u.roles LIKE :role'
            )->setParameter('role', '%"'.$role.'"%')->getResult();

    }
}