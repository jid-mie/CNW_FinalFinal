<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case CUSTOMER = 'customer';
    case STAFF = 'staff';
}
