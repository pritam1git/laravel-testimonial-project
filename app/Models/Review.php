<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    protected $fillable = [
        'username',
        'review_date',
        'review_star',
        'user_image',
        'review_text',
    ];
}

?>