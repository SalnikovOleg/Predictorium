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
