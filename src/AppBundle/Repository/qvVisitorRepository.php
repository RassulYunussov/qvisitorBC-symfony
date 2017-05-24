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
	
	public function findVisitorByOrder($qvOrder)
	{
		$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('SELECT qvvisitor.id, qvvisitor.firstname, qvvisitor.lastname, qvvisitor.patronimic FROM qvvisitor left join rf_visitor_order on rf_visitor_order.visitorid = qvvisitor.id left join qvorder on qvorder.id = rf_visitor_order.orderid where qvorder.id = :id');
		$statement->bindValue(1, $qvOrder);
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
	}

	public function findVisitorByUser($qvUser)
	{
		$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('SELECT qvvisitor.id, qvvisitor.firstname, qvvisitor.lastname, qvvisitor.patronimic, qvvisitor.birthdate, qvvisitor.genderid from qvvisitor 
									left join rf_visitor_order on rf_visitor_order.visitorid = qvvisitor.id
									left join qvorder on qvorder.id = rf_visitor_order.orderid
									left join qvuser on qvuser.id = qvorder.userid
									where qvuser.id = ? group by qvvisitor.id');
		$statement->bindValue(1, $qvUser);
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
	}
}
