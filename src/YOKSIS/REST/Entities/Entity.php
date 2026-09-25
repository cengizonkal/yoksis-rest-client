<?php


namespace Conkal\YOKSIS\REST\Entities;


#[\AllowDynamicProperties]
abstract class Entity implements \JsonSerializable
{
    /**
     * @param array|object|null $filaments Entity alanlarını dolduracak veri
     */
    public function __construct($filaments = null)
    {
        if ($filaments) {
            $this->fill($filaments);
        }
    }

    /**
     * @param array|object $filaments
     * @return $this
     */
    public function fill($filaments)
    {
        if (is_object($filaments)) {
            $this->fillFromArray(get_object_vars($filaments));
        } elseif (is_array($filaments)) {
            $this->fillFromArray($filaments);
        }
        return $this;
    }

    private function fillFromArray(array $fill)
    {
        foreach ($fill as $key => $var) {
            $this->{$key} = $var;
        }
    }

    /**
     * Entity'yi iç içe entity'ler dahil diziye çevirir.
     *
     * @return array
     */
    public function toArray()
    {
        return self::normalize(get_object_vars($this));
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    private static function normalize($value)
    {
        if ($value instanceof Entity) {
            return $value->toArray();
        }
        if ($value instanceof \stdClass) {
            $value = get_object_vars($value);
        }
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = self::normalize($item);
            }
        }
        return $value;
    }

}
