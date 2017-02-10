<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;


/**
 * qvLeaserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class qvLeaserRepository extends \Doctrine\ORM\EntityRepository
{
	
	public function getLeasersDetailedRaw()
	{
		$conn = $this->getEntityManager()->getConnection();
		$statement = $conn->prepare('Select qvleaser.id,qvleaser.bin, qvleaser.name, count(qvsector.id) countS,count(qvfloor.id) countf from qvleaser 
										left join qvcontract on qvcontract.leaserid = qvleaser.id
										left join rf_contract_sector on rf_contract_sector.contractid = qvcontract.id
										left join qvsector on qvsector.id = rf_contract_sector.sectorid
										left join qvfloor on qvfloor.id = qvsector.floorid
										group by qvleaser.id,qvleaser.name');
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
	}
}
