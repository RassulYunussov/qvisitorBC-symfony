<?php // src/AppBundle/Form/Model/ChangePassword.php
namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{
	/**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
     protected $oldPassword;

     protected $newPassword;

     public function getoldPassword()
     {
     	return $this->oldPassword;
     }
     public function getnewPassword()
     {
     	return $this->newPassword;
     }
     public function setoldPassword($oldPassword)
     {
     	 $this->oldPassword = $oldPassword;
     }
     public function setnewPassword($newPassword)
     {
     	 $this->newPassword = $newPassword;
     }
}