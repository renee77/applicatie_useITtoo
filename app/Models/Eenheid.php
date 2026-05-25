<?php

namespace App\Models;

enum Eenheid: string
{
    case Kilogram = 'kg';
    case Gram = 'gr';
    case Stuks = 'stuks';
    case Mililiter = 'ml';
}
