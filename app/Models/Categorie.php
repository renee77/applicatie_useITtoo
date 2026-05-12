<?php

namespace App\Models;

enum Categorie: string
{
    case Groente = 'Groenten';
    case Fruit = 'Fruit';
    case LangerHoudbaar = 'Langer Houdbare producten';
}
