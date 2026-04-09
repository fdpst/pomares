<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Gasto;

class GastoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gasto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
          'codigo'      => Str::random(5),
          'descripcion' => $this->faker->text(30),
          'importe'     => $this->faker->randomFloat(2, 10, 250),
          'created_at'  => $this->faker->dateTimeInInterval('-1 years', '+1 years', null)
      ];
    }
}
