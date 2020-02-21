<?php

use App\Enums\FieldType;
use App\Models\Field;
use App\Models\Subscriber;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    private function generateValidValue(FieldType $fieldType) {
        $faker = new Faker();
        $faker->addProvider(new \Faker\Provider\Base($faker));
        $faker->addProvider(new \Faker\Provider\Lorem($faker));
        $faker->addProvider(new \Faker\Provider\DateTime($faker));
        $faker->addProvider(new \Faker\Provider\Miscellaneous($faker));

        $value = null;
        switch ($fieldType) {
            case FieldType::STRING():
                $value = $faker->word;
                break;
            case FieldType::BOOLEAN():
                $value = $faker->boolean;
                break;
            case  FieldType::NUMBER():
                $value = $faker->randomNumber();
                break;
            case FieldType::DATE():
                $value = $faker->date();
                break;
        }
        return $value;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = factory(Field::class, 5)->create();
        for ($i=0; $i < 5000; $i++) {
            $subscribers = factory(Subscriber::class, 200)->create();
            $subscribers->each(function (Subscriber $subscriber) use ($fields) {
                $fields->each(function(Field $field) use ($subscriber) {
                    $subscriber->fields()->attach($field->id, ['value' => $this->generateValidValue($field->type)]);
                });
            });
        }
    }
}
