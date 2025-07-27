<?php

namespace app\models\forms;

use app\components\mdm\models\AuthItem;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int|null $created_at
 *
 * @property AuthItem $itemName
 */
class WeatherForms extends Model
{
    public $start_date;
    public $end_date;
    public $search;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [["start_date", "end_date", 'search'], 'required', 'on' => 'history'],            
            [["start_date", "end_date", 'search'], 'safe'],
            // [
            //     ['start_date', 'compare', 'compareAttribute' => 'end_date', 'operator' => '<', 'message' => 'Start date must be earlier than end date.'],
            //     ['end_date', 'validateDateDifference'],
            // ]
        ];
    }

    public function validateDateDifference($attribute, $params)
    {
        $startDate = strtotime($this->start_date);
        $endDate = strtotime($this->end_date);

        if ($startDate && $endDate) {
            $difference = ($endDate - $startDate) / (60 * 60 * 24); // Difference in days
            if ($difference > 3) {
                $this->addError($attribute, 'The date difference must not exceed 3 days.');
            }
        }
    }
}
