<?php

namespace App\Models;

use Database\Factories\ContactInquiryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'organisation', 'phone', 'email', 'product_interest', 'message', 'mailed_at'])]
class ContactInquiry extends Model
{
    /** @use HasFactory<ContactInquiryFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mailed_at' => 'datetime',
        ];
    }
}
