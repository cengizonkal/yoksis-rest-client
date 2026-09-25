<?php

namespace Conkal\YOKSIS\REST\Exceptions;

/**
 * 401/403: Kullanıcı adı, şifre ya da servis yetkisi hatalı.
 */
class AuthenticationException extends RequestFailedException
{
}
