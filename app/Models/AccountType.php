<?php

namespace App\Models;

enum AccountType: string
{
    case Beheer = 'beheer';
    case Klant = 'klant';
}
