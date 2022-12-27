<?php

namespace App\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Nuwave\Lighthouse\Pagination\PaginationArgs;
use Nuwave\Lighthouse\Pagination\PaginationType;
use Nuwave\Lighthouse\Schema\Directives\RelationDirectiveHelpers;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class OracyDirective extends BaseDirective implements FieldResolver
{
    // TODO implement the directive https://lighthouse-php.com/master/custom-directives/getting-started.html

    public static function definition(): string
    {
        return
            /** @lang GraphQL */
            <<<'GRAPHQL'
directive @oracy on FIELD_DEFINITION
GRAPHQL;
    }

    /**
     * Set a field resolver on the FieldValue.
     *
     * This must call $fieldValue->setResolver() before returning
     * the FieldValue.
     *
     * @param  \Nuwave\Lighthouse\Schema\Values\FieldValue  $fieldValue
     * @return \Nuwave\Lighthouse\Schema\Values\FieldValue
     */
    use RelationDirectiveHelpers;
    /**
     * @var array<string, mixed>
     */
    protected $lighthouseConfig;

    /**
     * TODO use Illuminate\Database\ConnectionResolverInterface when we drop support for Laravel < 6.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $database;

    public function __construct(ConfigRepository $configRepository, DatabaseManager $database)
    {
        $this->lighthouseConfig = $configRepository->get('lighthouse');
        $this->database = $database;
    }
    public function resolveField(FieldValue $fieldValue)
    {
        $fieldValue->setResolver(function (Model $parent, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
            $relationName = $this->relation();
            // $decorateBuilder = $this->makeBuilderDecorator($resolveInfo);
            // $paginationArgs = $this->paginationArgs($args);
            return $parent->getCheckedByField($relationName)->toArray();
        });
        return $fieldValue;
    }
    /**
     * @param  array<string, mixed>  $args
     */
    protected function paginationArgs(array $args): ?PaginationArgs
    {
        $paginationType = $this->paginationType();

        return null !== $paginationType
            ? PaginationArgs::extractArgs($args, $paginationType, $this->paginationMaxCount())
            : null;
    }
    protected function paginationType(): ?PaginationType
    {
        $type = $this->directiveArgValue('type');

        return null !== $type
            ? new PaginationType($type)
            : null;
    }
    protected function paginationMaxCount(): ?int
    {
        return $this->directiveArgValue('maxCount', $this->lighthouseConfig['pagination']['max_count']);
    }
}
