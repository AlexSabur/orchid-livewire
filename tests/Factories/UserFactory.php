<?php

namespace Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Orchid\Platform\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'           => $this->faker->firstName,
            'email'          => $this->faker->unique()->safeEmail,
            'password'       => Hash::make('password'),
            'remember_token' => Str::random(10),
            'permissions'    => [
                'platform.index'                      => 1,
                'platform.systems'                    => 1,
                'platform.systems.index'              => 1,
                'platform.systems.roles'              => 1,
                'platform.systems.settings'           => 1,
                'platform.systems.users'              => 1,
                'platform.systems.comment'            => 1,
                'platform.systems.attachment'         => 1,
                'platform.systems.media'              => 1,
            ],
        ];
    }
}
