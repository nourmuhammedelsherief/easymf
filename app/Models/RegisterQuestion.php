<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterQuestion extends Model
{
    use HasFactory;
    protected $table = 'register_questions';
    protected $fillable = [
        'question' , 'question_en'
    ];

    public function getQuestionLangAttribute(){
        return app()->getLocale() == 'ar'  ? $this->question : $this->question_en;
    }

   
}
