<?php

namespace AppBundle\Repository;

/**
 * qvBuildingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class qvBuildingRepository extends \Doctrine\ORM\EntityRepository
{
	/* public function countSectorInBuilding($build){
     	return $this->getEntityManager()
     	->createQuery(
    		'SELECT COUNT(sector) FROM AppBundle:qvSector sector join sector.floor floor join floor.building build  
    			WHERE build = :name'
			)->setParameter('name', $build)->getSingleScalarResult();
      }
       public function countFloorInBuild($build){
     	return $this->getEntityManager()
     	->createQuery(
    		'SELECT COUNT(fl) FROM AppBundle:qvFloor fl 
    			WHERE fl.building = :name'
			)->setParameter('name', $build)->getSingleScalarResult();
      }*/
}
