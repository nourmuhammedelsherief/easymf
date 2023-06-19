<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicQuestion extends Model
{
    use HasFactory;
    protected $table = 'public_questions';
    protected $fillable = [
        'question' , 'answer', 'question_en' , 'answer_en'
    ];

    public function getQuestionLangAttribute(){
        return app()->getLocale() == 'ar' ? $this->question : $this->question_en;
    }

    public function getAnswerLangAttribute(){
        return app()->getLocale() == 'ar' ? $this->answer : $this->answer_en;
    }
}
