<?php
declare(strict_types = 1);
namespace TYPO3\CMS\Security\Permission;

use TYPO3\CMS\Security\Permission\PermissionListNotFoundException;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

interface PermissionProviderInterface
{
    /**
     * Returns the list that belongs to the given object identity.
     *
     * @param ObjectIdentityInterface $oid
     * @param SubjectIdentityInterface[] $sids
     * @return PermissionListInterface
     * @throws PermissionListNotFoundException when there is no list
     */
    public function findList(ObjectIdentityInterface $objectIdentities, array $subjectIdentities = []): PermissionListInterface;
}