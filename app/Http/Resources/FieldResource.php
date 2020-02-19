<?php

namespace App\Http\Resources;

use App\Enums\FieldType;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{

    private function castPivotValue($value, FieldType $fieldType) {
        switch ($fieldType) {
            case FieldType::BOOLEAN():
                $value = boolval($value);
                break;
            case  FieldType::NUMBER():
                $value = (int) $value;
                break;
        }
        return $value;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
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
