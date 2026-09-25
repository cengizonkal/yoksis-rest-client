<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\Entities\YerlestirmeVeri;
use Conkal\YOKSIS\REST\Pagination\Page;

class PaginationTest extends TestCase
{
    private static function page(array $ids, $number, $totalPages, $size = 2)
    {
        return self::json([
            'content' => array_map(function ($id) {
                return ['tcKimlikNo' => (string)$id];
            }, $ids),
            'totalElements' => 5,
            'totalPages' => $totalPages,
            'number' => $number,
            'size' => $size,
            'first' => $number === 0,
            'last' => $number + 1 >= $totalPages,
        ]);
    }

    public function test_paginate_keeps_page_metadata()
    {
        $page = $this->client([self::page([1, 2], 0, 3)])
            ->yerlestirmeVeri()->paginate(['tur' => 'YKS', 'yil' => '2019', 'page' => 0, 'size' => 2]);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertSame(5, $page->getTotalElements());
        $this->assertSame(3, $page->getTotalPages());
        $this->assertSame(0, $page->getPageNumber());
        $this->assertSame(2, $page->getPageSize());
        $this->assertTrue($page->isFirst());
        $this->assertTrue($page->hasMorePages());
        $this->assertCount(2, $page);
        $this->assertInstanceOf(YerlestirmeVeri::class, $page[0]);
        $this->assertSame('2', $page[1]->tcKimlikNo);
        $this->assertSame('tur=YKS&yil=2019&page=0&size=2', $this->lastRequest()->getUri()->getQuery());
    }

    public function test_page_is_iterable_and_serializable()
    {
        $page = $this->client([self::page([1, 2], 0, 1)])->yerlestirmeVeri()->paginate();

        $ids = [];
        foreach ($page as $item) {
            $ids[] = $item->tcKimlikNo;
        }
        $this->assertSame(['1', '2'], $ids);
        $this->assertFalse($page->hasMorePages());
        $this->assertSame(5, json_decode(json_encode($page), true)['totalElements']);
    }

    public function test_query_still_returns_plain_array()
    {
        $items = $this->client([self::page([1, 2], 0, 1)])->yerlestirmeVeri()->query(['tur' => 'YKS']);

        $this->assertIsArray($items);
        $this->assertCount(2, $items);
    }

    public function test_cursor_walks_all_pages_lazily()
    {
        $client = $this->client([self::page([1, 2], 0, 3), self::page([3, 4], 1, 3), self::page([5], 2, 3)]);

        $ids = [];
        foreach ($client->yerlestirmeVeri()->cursor(['tur' => 'YKS'], 2) as $item) {
            $ids[] = $item->tcKimlikNo;
        }

        $this->assertSame(['1', '2', '3', '4', '5'], $ids);
        $this->assertCount(3, $this->history);
        $this->assertSame('tur=YKS&size=2&page=2', $this->lastRequest()->getUri()->getQuery());
    }

    public function test_cursor_stops_when_server_ignores_page_parameter()
    {
        // Servis her istekte ilk sayfayı döndürüyor: sonsuz döngüye girmemeli.
        $client = $this->client([self::page([1, 2], 0, 3), self::page([1, 2], 0, 3), self::page([1, 2], 0, 3)]);

        $items = iterator_to_array($client->yerlestirmeVeri()->cursor(), false);

        $this->assertCount(4, $items);
        $this->assertCount(2, $this->history);
    }

    public function test_cursor_respects_max_pages()
    {
        $client = $this->client([self::page([1, 2], 0, 100), self::page([3, 4], 1, 100)]);

        $items = iterator_to_array($client->yerlestirmeVeri()->cursor([], null, 2), false);

        $this->assertCount(4, $items);
        $this->assertCount(2, $this->history);
    }

    public function test_page_infers_last_page_without_last_field()
    {
        $page = new Page([], (object)['number' => 2, 'totalPages' => 3]);
        $this->assertTrue($page->isLast());
        $this->assertFalse($page->isFirst());

        $this->assertTrue((new Page([]))->isLast());
    }

    public function test_plain_list_response_is_a_single_page()
    {
        $page = $this->client([self::json([['tcKimlikNo' => '1']])])->yerlestirmeVeri()->paginate();

        $this->assertCount(1, $page);
        $this->assertTrue($page->isLast());
        $this->assertNull($page->getTotalElements());
    }
}
