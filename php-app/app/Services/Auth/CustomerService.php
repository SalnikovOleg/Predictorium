<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    public function __construct(
        protected CustomerRepository $repository,
    ) {}

    public function register(array $data): User
    {
        $customer = $this->repository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $customer->assignRole('customer');

        return $customer;
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['current_password'])) {
            if (! Hash::check($data['current_password'], $user->password)) {
                throw new \App\Exceptions\InvalidPasswordException('Current password is incorrect');
            }
            unset($data['current_password']);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($user, $data);
    }

    public function login(array $data): ?array
    {
        $customer = $this->repository->findByEmail($data['email']);

        if (! $customer || ! Hash::check($data['password'], $customer->password)) {
            return null;
        }

        $token = $customer->createToken('auth-token')->plainTextToken;

        return [
            'customer' => $customer,
            'token' => $token,
        ];
    }
}
