<?php
namespace App\Response\Artic;

class ListPagination
{
    public int $total;
    public int $limit;
    public int $offset;
    public int $totalPages;
    public int $currentPage;
}