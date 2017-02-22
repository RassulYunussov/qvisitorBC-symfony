<?php

namespace AppBundle\Repository;

/**
 * qvUserPassportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class qvUserPassportRepository extends \Doctrine\ORM\EntityRepository
{
	public function findUserpassportByUserRole()
	{
	 return $this->getEntityManager()
	 ->createQuery(
                'SELECT passport, urole FROM AppBundle:qvUserPassport passport JOIN passport.user user JOIN user.role urole WHERE urole.code = :name'
                    )->setParameter('name', 'ROLE_CHECKPOINT')->getResult();
	}
	public function findUserpassportByEntrance($qvEntrance)
	{
		$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('SELECT qvuserpassport.id, qvuserpassport.userid,qvuserpassport.lastname, qvuserpassport.firstname, qvuserpassport.patronimic from 							qvuserpassport 
									left join qvuser on qvuser.id = qvuserpassport.userid
									left join qvorder on qvorder.userid = qvuser.id
									left join qventrance on qventrance.orderid = qvorder.id
									where qventrance.id = ?');
		$statement->bindValue(1, $qvEntrance->getId());
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
	}
	public function findUserpassportByLeaser($qvLeaser)
	{
		$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('SELECT qvuserpassport.id, qvuserpassport.userid,qvuserpassport.lastname, qvuserpassport.firstname, qvuserpassport.patronimic from 							qvuserpassport 
									left join qvuser on qvuser.id = qvuserpassport.userid 
									left join qvleaser on qvuser.leaserid = qvuser.leaserid 
									where qvuser.leaserid = ?');
		$statement->bindValue(1, $qvLeaser->getId());
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
	}

		public function findUserByPassport($passport) {
		return $this->getEntityManager()
		            ->createQuery(
			'SELECT user.id, user.login, user.password, user.disabled FROM AppBundle:qvUserPassport passport 
					LEFT JOIN passport.user user 
    					WHERE passport.id = :id'
		)->setParameter('id', $passport)->getResult();
	}

}
