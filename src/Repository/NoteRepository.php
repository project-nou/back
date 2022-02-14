<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\Note;
use App\Entity\User;
use App\Exception\NoteAlreadyExist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function create(User $user, Group $group, string $format, string $content)
    {
        //if ($this->findOneBy(['author' => $user, "group" => $group,])) throw new NoteAlreadyExist();
        $note = new Note;
        $this->_em->beginTransaction();
        try {
            $note
                ->setAuthor($user)
                ->setGroup($group)
                ->setContent($content)
                ->setFormat($format)
                ->setIsDone(false);
            $this->_em->persist($note);
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $exception) {
            $this->_em->rollback();
            throw new \Exception();
        }
    }
}
