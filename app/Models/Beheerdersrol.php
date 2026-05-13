<?php 

namespace App\Models;

enum Beheerdersrol: string
{
  case voorraadbeheerder = "vooraadbeheerder"; 
  case klantenservice = "klantenservice";
}