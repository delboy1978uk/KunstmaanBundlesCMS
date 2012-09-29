<?php

namespace Kunstmaan\AdminBundle\Entity;

use Kunstmaan\AdminBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * omnext edit command
 *
 * @ORM\Entity
 * @ORM\Table(name="kuma_edit_commands")
 *
 * @todo This should be removed when refactoring (logging should happen via a Listener)
 * @deprecated This will be removed
 */
class EditCommand extends Command
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @param EntityManager $em   The entity manger
     * @param User          $user The user
     */
    public function __construct(EntityManager $em, User $user)
    {
        $this->em = $em;
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function executeimpl($options)
    {
        $this->em->persist($options['entity']);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeimpl()
    {
        // TODO extra actions
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}