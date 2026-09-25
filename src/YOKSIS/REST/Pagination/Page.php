<?php

namespace Conkal\YOKSIS\REST\Pagination;

use Conkal\YOKSIS\REST\Entities\Entity;

/**
 * Sayfalı bir servis yanıtı: kayıtlar ve sayfa bilgileri.
 *
 * YÖKSİS'in sayfalı servisleri Spring Data biçiminde yanıt döndürür
 * (content, totalElements, totalPages, number, size, first, last).
 * Yanıtta bulunmayan bilgiler için getter'lar null döndürür.
 *
 * Dizi gibi kullanılabilir: foreach, count() ve $page[0] desteklenir.
 */
class Page implements \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    /**
     * @var Entity[]
     */
    private $items;

    /**
     * @var object
     */
    private $raw;

    /**
     * @param Entity[] $items
     * @param object|null $raw Ham yanıt (content dışındaki alanlar okunur)
     */
    public function __construct(array $items, $raw = null)
    {
        $this->items = array_values($items);
        $this->raw = is_object($raw) ? $raw : new \stdClass();
    }

    /**
     * @return Entity[]
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @return int|null Toplam kayıt sayısı
     */
    public function getTotalElements()
    {
        return $this->intField('totalElements');
    }

    /**
     * @return int|null Toplam sayfa sayısı
     */
    public function getTotalPages()
    {
        return $this->intField('totalPages');
    }

    /**
     * @return int|null Sayfa numarası (0'dan başlar)
     */
    public function getPageNumber()
    {
        return $this->intField('number');
    }

    /**
     * @return int|null Sayfa boyutu
     */
    public function getPageSize()
    {
        return $this->intField('size');
    }

    /**
     * @return bool
     */
    public function isFirst()
    {
        if (isset($this->raw->first)) {
            return (bool)$this->raw->first;
        }
        $number = $this->getPageNumber();
        return $number === null || $number === 0;
    }

    /**
     * Son sayfa mı? Yanıtta bilgi yoksa sayfa numarası ve toplam sayfa
     * sayısından, o da yoksa sayfanın boş olmasından çıkarılır.
     *
     * @return bool
     */
    public function isLast()
    {
        if (isset($this->raw->last)) {
            return (bool)$this->raw->last;
        }
        $number = $this->getPageNumber();
        $total = $this->getTotalPages();
        if ($number !== null && $total !== null) {
            return $number + 1 >= $total;
        }
        return count($this->items) === 0;
    }

    /**
     * @return bool
     */
    public function hasMorePages()
    {
        return !$this->isLast();
    }

    /**
     * @return object Ham yanıt (content hariç tüm alanlar dahil)
     */
    public function getRaw()
    {
        return $this->raw;
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->items);
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Page salt okunurdur.');
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new \LogicException('Page salt okunurdur.');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'items' => $this->items,
            'totalElements' => $this->getTotalElements(),
            'totalPages' => $this->getTotalPages(),
            'number' => $this->getPageNumber(),
            'size' => $this->getPageSize(),
            'first' => $this->isFirst(),
            'last' => $this->isLast(),
        ];
    }

    private function intField($name)
    {
        return isset($this->raw->{$name}) && is_numeric($this->raw->{$name}) ? (int)$this->raw->{$name} : null;
    }
}
