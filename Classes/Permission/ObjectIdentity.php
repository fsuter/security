<?php
declare(strict_types = 1);
namespace TYPO3\CMS\Security\Permission;

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

 /**
  * A basic object identity implementation.
  */
class ObjectIdentity implements ObjectIdentityInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @param string $identifier
     * @throws \InvalidArgumentException
     */
    public function __construct(string $identifier)
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException('$identifier cannot be empty.');
        }

        $this->identifier = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(ObjectIdentityInterface $identity): bool
    {
        return $this->identifier === $identity->getIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function __toString()
    {
        return sprintf('t3:/security/permission/object/%s', $this->identifier);
    }
}