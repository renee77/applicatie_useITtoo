<?php

namespace App\Models;

enum AccountType: string 
{
  case klant = "klant";
  case beheerder = "beheer";
}