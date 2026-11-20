<?php
namespace Database\Factories;
use App\Models\Comercio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class ComercioFactory extends Factory
{
 protected $model = Comercio::class;
 public function definition(): array {return ['user_id'=>User::factory(),'nombre'=>$this->faker->company()];}
}
