<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 25.07.2018
 * Time: 11:26
 */

namespace App\Twig;

use App\Entity\Genus;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EasyAdminExtension extends \Twig_Extension
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'filter_admin_actions',
                [$this, 'filterActions']
            )
        ];
    }

    public function filterActions(array $itemActions, $item)
    {
        if ($item instanceof Genus && $item->getIsPublished()) {
            unset($itemActions['delete']);
        }

        // export action is rendered by us manually
        unset($itemActions['export']);

        if ($item instanceof User && !$this->authorizationChecker->isGranted('ROLE_SUPERADMIN')) {
            unset($itemActions['edit']);
        }

        return $itemActions;
    }
}