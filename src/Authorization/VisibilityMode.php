<?php

namespace Matheusmarnt\Scoutify\Authorization;

enum VisibilityMode: string
{
    case Any = 'any';
    case All = 'all';
}
