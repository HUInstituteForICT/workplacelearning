<?php

declare(strict_types=1);

namespace App\Repository;

interface Searchable
{
    /**
     * Search this repository with the set filters, order and items per page.
     */
    public function search(array $filters = [], ?int $itemsPerPage = 25);

    /**
     * Get the search filters that can be used on this repository.
     */
    public function getSearchFilters(): array;
}
