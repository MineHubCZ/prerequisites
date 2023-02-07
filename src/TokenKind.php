<?php

namespace MineHub\Prerequisities;

enum TokenKind
{
    case Or;
    case And;
    case Not;
    case Open;
    case Close;
    case Variable;
}
