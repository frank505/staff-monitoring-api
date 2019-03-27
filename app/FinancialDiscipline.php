<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialDiscipline extends Model
{
    //
    protected $table = "financial_discipline";
    protected $fillable = ["task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
    "month","year","staff_punishement_type","admin_complaints"];
}
