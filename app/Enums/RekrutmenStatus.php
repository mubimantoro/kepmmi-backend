<?php

namespace App\Enums;

enum RekrutmenStatus: string
{
    case Pending = 'Menunggu Konfirmasi';
    case Success = 'Diterima';
    case Rejected = 'Ditolak';
}
