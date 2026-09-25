<?php

namespace Conkal\YOKSIS\REST\Exceptions;

/**
 * Kütüphanenin fırlattığı tüm istisnaların ortak arayüzü.
 *
 * <code>
 * try {
 *     $client->pedagojikFormasyon()->create($kayit);
 * } catch (YoksisException $e) {
 *     // YÖKSİS kaynaklı her türlü hata
 * }
 * </code>
 */
interface YoksisException extends \Throwable
{
}
