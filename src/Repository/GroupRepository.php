<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Exception\GroupExist;
use App\Exception\GroupNotFound;
use Cassandra\Date;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }


    private function save(Group $group)
    {
        $this->_em->persist($group);
        $this->_em->flush();
    }


    public function create(string $name, User $user)
    {
        $isExist = self::findOneByName($name);
        if ($isExist && $user === $isExist->getAdmin()) {
            throw new GroupExist($name);
        }
        $group = new Group();
        $this->_em->beginTransaction();
        try {
            $group
                ->setIsActive(true)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setName($name)
                ->addParticipant($user)
                ->setAdmin($user);
            self::save($group);
            $this->_em->commit();
        } catch (\Exception $exception) {
            $this->_em->rollback();
            throw new \Exception();
        }
    }

    public function addParticipant(string $name, User $user)
    {
        $group = self::findOneByName($name);
        $this->_em->beginTransaction();
        try {
            $group->addParticipant($user);
            self::save($group);
            $this->_em->commit();
        } catch (\Exception $exception) {
            $this->_em->rollback();
            throw new \Exception();
        }
    }

    public function remove(string $name, User $user)
    {
        $group = self::findOneByName($name);
        if (!$group && $user !== $group->getAdmin()) {
            throw new GroupNotFound($name);
        }
        $this->_em->beginTransaction();
        try {
            $group->setIsActive(false);
            self::save($group);
            $this->_em->commit();
        } catch (\Exception $exception) {
            $this->_em->rollback();
            throw new \Exception();
        }
    }

    public function changeAdmin(int $groupId, User $user)
    {
        $group = self::findOneById($groupId);
        $this->_em->beginTransaction();
        try {
            $group->setAdmin($user);
            self::save($group);
            $this->_em->commit();
        } catch (\Exception $exception) {
            $this->_em->rollback();
            throw new \Exception();
        }
    }
}
