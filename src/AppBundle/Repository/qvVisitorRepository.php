<?php

namespace AppBundle\Repository;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Connection;

/**
 * qvVisitorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class qvVisitorRepository extends \Doctrine\ORM\EntityRepository
{
	public function findVisitorsByOrder($qvOrder){
		return $this->getEntityManager()
		->createQuery('SELECT visitor from AppBundle:qvVisitor visitor join visitor.orders order where order.id= :id')
		->setParameter('id', $qvOrder)->getResult();
	}

	public function getVisitor($qvUser)
	{
		$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('SELECT qvvisitor.id, qvvisitor.firstname, qvvisitor.lastname, qvvisitor.patronimic from qvvisitor 
									left join rf_visitor_order on rf_visitor_order.visitorid = qvvisitor.id
									left join qvorder on qvorder.id = rf_visitor_order.orderid
									left join qvuser on qvuser.id = qvorder.userid
									where qvuser.id = ? group by qvvisitor.id');
		$statement->bindValue(1, $qvUser);
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
		/*$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('Select visitor from AppBundle:qvVisitor 
										left join qvcontract on qvcontract.leaserid = qvleaser.id
										left join rf_contract_sector on rf_contract_sector.contractid = qvcontract.id
										left join qvsector on qvsector.id = rf_contract_sector.sectorid
										left join qvfloor on qvfloor.id = qvsector.floorid
										group by qvleaser.id,qvleaser.name');
		*/
	}
}
