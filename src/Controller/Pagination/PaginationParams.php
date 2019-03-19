<?php

namespace App\Controller\Pagination;

use Symfony\Component\HttpFoundation\Request;

class PaginationParams
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @param Request $request
     * @return PaginationParams
     */
    public static function fromRequest(Request $request): PaginationParams
    {
        $limit = $request->get('limit', PaginatedCollection::PER_PAGE);
        $offset = $request->get('offset', 0);

        $paginationParams = new PaginationParams();
        $paginationParams->offset = $offset;
        $paginationParams->limit = $limit;
        return $paginationParams;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}