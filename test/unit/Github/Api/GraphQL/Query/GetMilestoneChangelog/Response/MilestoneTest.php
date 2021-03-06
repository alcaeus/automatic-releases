<?php

declare(strict_types=1);

namespace Doctrine\AutomaticReleases\Test\Unit\Github\Api\GraphQL\Query\GetMilestoneChangelog\Response;

use Doctrine\AutomaticReleases\Github\Api\GraphQL\Query\GetMilestoneChangelog\Response\IssueOrPullRequest;
use Doctrine\AutomaticReleases\Github\Api\GraphQL\Query\GetMilestoneChangelog\Response\Milestone;
use PHPUnit\Framework\TestCase;

final class MilestoneTest extends TestCase
{
    public function test() : void
    {
        $milestone = Milestone::fromPayload([
            'number'       => 123,
            'closed'       => true,
            'title'        => 'The title',
            'description'  => 'The description',
            'issues'       => [
                'nodes' => [
                    [
                        'number' => 456,
                        'title'  => 'Issue',
                        'author' => [
                            'login' => 'Magoo',
                            'url'   => 'http://example.com/author',
                        ],
                        'url'    => 'http://example.com/issue',
                        'closed' => true,
                        'labels' => [
                            'nodes' => [],
                        ],
                    ],
                ],
            ],
            'pullRequests' => [
                'nodes' => [
                    [
                        'number' => 789,
                        'title'  => 'PR',
                        'author' => [
                            'login' => 'Magoo',
                            'url'   => 'http://example.com/author',
                        ],
                        'url'    => 'http://example.com/issue',
                        'merged' => true,
                        'closed' => false,
                        'labels' => [
                            'nodes' => [],
                        ],
                    ],
                ],
            ],
            'url'          => 'http://example.com/milestone',
        ]);

        self::assertEquals(
            [
                IssueOrPullRequest::fromPayload([
                    'number' => 456,
                    'title'  => 'Issue',
                    'author' => [
                        'login' => 'Magoo',
                        'url'   => 'http://example.com/author',
                    ],
                    'url'    => 'http://example.com/issue',
                    'closed' => true,
                    'labels' => [
                        'nodes' => [],
                    ],
                ]),
                IssueOrPullRequest::fromPayload([
                    'number' => 789,
                    'title'  => 'PR',
                    'author' => [
                        'login' => 'Magoo',
                        'url'   => 'http://example.com/author',
                    ],
                    'url'    => 'http://example.com/issue',
                    'merged' => true,
                    'closed' => false,
                    'labels' => [
                        'nodes' => [],
                    ],
                ]),
            ],
            $milestone->entries()
        );

        self::assertSame(123, $milestone->number());
        self::assertTrue($milestone->closed());
        self::assertSame('http://example.com/milestone', $milestone->url()->__toString());
        self::assertSame('The title', $milestone->title());
        self::assertSame('The description', $milestone->description());
        $milestone->assertAllIssuesAreClosed();
    }
}
