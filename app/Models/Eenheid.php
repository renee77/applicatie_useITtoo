<?php

namespace App\Models;

enum Eenheid: string
{
    case Kilogram = 'kg';
    case Gram = 'gr';
    case Stuks = 'stuks';
    case Bos = 'per bos';
    case Mililiter = 'ml';
    case Pot = 'per pot';
}
