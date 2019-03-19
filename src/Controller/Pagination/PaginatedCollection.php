<?php

namespace App\Controller\Pagination;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatedCollection
{
    const PER_PAGE = 100;

    /**
     * @var array
     */
    protected $objects = [];

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var mixed
     */
    protected $additionalData = null;

    /**
     * @param QueryBuilder $queryBuilder
     * @param PaginationParams $paginationParams
     * @param callable|null $entityAccessor used to access the desired entity from query builder result
     *  in cases a nested entity of the result set is required
     * @param array|null $additionalData
     * @return PaginatedCollection
     */
    public static function fromQueryBuilder(
        QueryBuilder $queryBuilder,
        PaginationParams $paginationParams,
        ?callable $entityAccessor = null,
        ?array $additionalData = null
    ): PaginatedCollection
    {
        $limit = $paginationParams->getLimit();
        $offset = $paginationParams->getOffset();

        if (-1 !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        $queryBuilder->setFirstResult($offset);
        $collection = new PaginatedCollection();
        $paginator = new Paginator($queryBuilder->getQuery());

        $objects = $queryBuilder->getQuery()->getResult();
        if ($entityAccessor) {
            $objects = array_map($entityAccessor, $objects);
        }

        $collection->objects = $objects;
        $collection->limit = $limit;
        $collection->offset = $offset;
        $collection->total = $paginator->count();
        $collection->additionalData = $additionalData;

        return $collection;
    }

    /**
     * @param Collection $input
     * @param PaginationParams $paginationParams
     * @return PaginatedCollection
     */
    public static function fromCollection(
        Collection $input,
        PaginationParams $paginationParams
    ): PaginatedCollection
    {
        $limit = $paginationParams->getLimit();
        $offset = $paginationParams->getOffset();

        $collection = new PaginatedCollection();
        $collection->objects = $input->slice($offset, $limit);
        $collection->limit = $limit;
        $collection->offset = $offset;
        $collection->total = $input->count();

        return $collection;
    }

    /**
     * @param PaginationParams $paginationParams
     * @return PaginatedCollection
     */
    public static function emptyCollection(PaginationParams $paginationParams): PaginatedCollection
    {
        $limit = $paginationParams->getLimit();
        $offset = $paginationParams->getOffset();
        $collection = new PaginatedCollection();
        $collection->objects = [];
        $collection->limit = $limit;
        $collection->offset = $offset;
        $collection->total = 0;

        return $collection;
    }

    /**
     * @return array
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return array|null
     */
    public function getAdditionalData(): ?array
    {
        return $this->additionalData;
    }

    /**
     * @return bool
     */
    public function hasAdditionalData(): bool
    {
        return $this->additionalData != null;
    }
}