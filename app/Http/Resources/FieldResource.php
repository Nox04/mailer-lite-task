<?php

namespace App\Http\Resources;

use App\Enums\FieldType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FieldResource
 * @property mixed updated_at
 * @property mixed created_at
 * @property string title
 * @property FieldType type
 * @property mixed id
 */
class FieldResource extends JsonResource
{
    /**
     * Cast the field value to the respective type.
     * @param $value
     * @param FieldType $fieldType
     * @return bool|int
     */
    private function castPivotValue($value, FieldType $fieldType)
    {
        switch ($fieldType) {
            case FieldType::BOOLEAN():
                $value = boolval($value);
                break;
            case FieldType::NUMBER():
                $value = (int) $value;
                break;
            default:
                break;
        }
        return $value;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'value' => $this->whenPivotLoaded('field_subscriber', function () {
                return $this->castPivotValue($this->pivot->value, $this->type);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
