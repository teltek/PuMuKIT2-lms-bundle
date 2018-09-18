<?php

namespace Pumukit\LmsBundle\Services;

use Pumukit\SchemaBundle\Document\MultimediaObject;
use Pumukit\SchemaBundle\Services\MultimediaObjectService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\RequestStack;

class MultimediaObjectVoter extends Voter
{
    const PLAY = 'play';

    private $mmobjService;
    private $requestStack;
    private $ssoService;

    public function __construct(MultimediaObjectService $mmobjService, RequestStack $requestStack, SSOService $ssoService)
    {
        $this->mmobjService = $mmobjService;
        $this->requestStack = $requestStack;
        $this->ssoService = $ssoService;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::PLAY))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof MultimediaObject) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $multimediaObject, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
        case self::PLAY:
            return $this->canPlay($multimediaObject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    protected function canPlay($multimediaObject, $user = null)
    {
        $req = $this->requestStack->getMasterRequest();

        if (!$this->mmobjService->isHidden($multimediaObject, 'PUCHLMS')) {
            return false;
        }

        $refererUrl = $req->headers->get('referer');

        if (!$refererUrl) {
            return false;
        }

        $refererQuery = parse_url($refererUrl, PHP_URL_QUERY);
        if (!$refererQuery) {
            return false;
        }

        parse_str($refererQuery, $query);
        if (!isset($query['hash'])) {
            return false;
        }

        $hash = $query['hash'];
        if (!$this->ssoService->validateHash($hash, '')) {
            return false;
        }

        return true;
    }
}