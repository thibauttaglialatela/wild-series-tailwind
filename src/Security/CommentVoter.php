<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CommentVoter extends Voter
{
    private $security;

    const DELETE = "delete";
    const EDIT = "edit";

    /**
     * @param $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
            return false;
        }
        if (!$subject instanceof Comment) {
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $comment = $subject;

        return match($attribute) {
            self::EDIT => $this->canEdit($comment, $user),
            self::DELETE => $this->canDelete($comment, $user),
            default => throw new \LogicException('This code could not be reached!')
        };
    }

    private function canDelete(Comment $comment, User $user): bool
    {
        if ($this->canEdit($comment, $user)) {
            return true;
        }
        return false;
    }

    private function canEdit(Comment $comment, User $user):bool
    {
        return $user === $comment->getAuthor();
    }
}