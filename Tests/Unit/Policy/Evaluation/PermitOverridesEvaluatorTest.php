<?php
declare(strict_types = 1);

namespace TYPO3\CMS\Security\Tests\Unit\Policy\Evaluation;

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

use TYPO3\CMS\Core\ExpressionLanguage\Resolver;
use TYPO3\CMS\Security\Policy\Evaluation\PermitOverridesEvaluator;
use TYPO3\CMS\Security\Policy\PolicyDecision;
use TYPO3\CMS\Security\Policy\PolicyObligation;

/**
 * Test case
 */
class PermitOverridesEvaluatorTest extends AbstractEvaluatorTest
{
    public function processDataProvider()
    {
        return [
            [
                new PolicyDecision(PolicyDecision::PERMIT),
                [
                    [PolicyDecision::PERMIT],
                ],
            ],
            [
                new PolicyDecision(PolicyDecision::DENY),
                [
                    [PolicyDecision::DENY],
                ],
            ],
            [
                new PolicyDecision(PolicyDecision::NOT_APPLICABLE),
                [
                    [PolicyDecision::NOT_APPLICABLE],
                ],
            ],
            [
                new PolicyDecision(PolicyDecision::NOT_APPLICABLE),
                [],
            ],
            [
                new PolicyDecision(PolicyDecision::PERMIT),
                [
                    [PolicyDecision::DENY],
                    [PolicyDecision::DENY],
                    [PolicyDecision::DENY],
                    [PolicyDecision::PERMIT],
                ],
            ],
            [
                new PolicyDecision(
                    PolicyDecision::PERMIT,
                    ...[
                        new PolicyObligation('bar'),
                        new PolicyObligation('qux'),
                    ]
                ),
                [
                    [PolicyDecision::DENY],
                    [PolicyDecision::NOT_APPLICABLE],
                    [PolicyDecision::DENY, [['baz'], ['bar']]],
                    [PolicyDecision::PERMIT, [['bar'], ['qux']]],
                    [PolicyDecision::NOT_APPLICABLE],
                ],
            ],
            [
                new PolicyDecision(
                    PolicyDecision::DENY,
                    ...[
                        new PolicyObligation('bar'),
                        new PolicyObligation('baz'),
                        new PolicyObligation('bar'),
                    ]
                ),
                [
                    [PolicyDecision::DENY, [['bar']]],
                    [PolicyDecision::DENY],
                    [PolicyDecision::NOT_APPLICABLE],
                    [PolicyDecision::DENY, [['baz'], ['bar']]],
                ],
            ],
            [
                new PolicyDecision(
                    PolicyDecision::PERMIT,
                    ...[
                        new PolicyObligation('foo'),
                        new PolicyObligation('bar'),
                    ]
                ),
                [
                    [PolicyDecision::PERMIT, [['foo'], ['bar']]],
                    [PolicyDecision::PERMIT, [['baz'], ['bar']]],
                    [PolicyDecision::PERMIT],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider processDataProvider
     */
    public function processReturnsDecision(PolicyDecision $expected, array $evaluables)
    {
        $resolver = $this->prophesize(Resolver::class)->reveal();
        $subject = new PermitOverridesEvaluator();

        $this->assertEquals(
            $expected,
            $subject->process(
                $resolver,
                ...$this->buildEvaluables($evaluables)
            )
        );
    }
}
