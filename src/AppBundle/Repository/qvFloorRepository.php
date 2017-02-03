<?php

use AppBundle\Entity\qvBuilding;

namespace AppBundle\Repository;

/**
 * qvFloorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class qvFloorRepository extends \Doctrine\ORM\EntityRepository
{
	public function findBuildByFloor($id)
	{
        return $this->getEntityManager()
        ->createQuery(
          'SELECT bld.id from AppBundle:qvFloor fl LEFT JOIN fl.building bld 
         	 WHERE fl.id = :id'
            )->setParameter('id', $id)->getResult();
    }
    
    public function countFloorInBuild($build){
     	return $this->getEntityManager()
     	->createQuery(
    		'SELECT COUNT(fl) FROM AppBundle:qvFloor fl 
    			WHERE fl.building = :name'
			)->setParameter('name', $build)->getSingleScalarResult();
      }

    public function findFlooorByBuild($qvBuilding){
    	return $this->getEntityManager()
    	->createQuery(
            'SELECT fl.name, fl.id from AppBundle:qvFloor fl 
            WHERE fl.building = :name'
            )->setParameter('name', $qvBuilding)->getResult();
    }

}
