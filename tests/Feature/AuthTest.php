<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    function testRegisterSuccess() {

        $faker = \Faker\Factory::create();

        $this->post("/api/v1/auth/register", [
            "username" => $faker->name,
            "email" => $faker->email(),
            "password" => $faker->password()
        ])
        ->assertCreated();

        DB::table('users')->where("username", $faker->name)->delete();
    }

    function testRegisterFailed() {
        $this->post("/api/v1/auth/register", [
            "username" => "farish",
            "email" => "farish",
            "password" => ""
        ])
        ->assertBadRequest();
    }

    function testRegisterUserAlreadyExist() {
        $this->post("/api/v1/auth/register", [
            "username" => "admin",
            "email" => "admin@gmail.com",
            "password" => "admin"
        ])
        ->assertUnauthorized();
    }

    function testLoginSuccess() {
        $this->post("/api/v1/auth/login", [
            "email" => "farish@gmail.com",
            "password" => "farish"
        ])
        ->assertOk();
    }

    function testLoginWrongPassword() {
        $this->post("/api/v1/auth/login", [
            "email" => "admin@gmail.com",
            "password" => "salah"
        ])
        ->assertUnauthorized();
    }

    function testLoginAccountNotExist() {
        $this->post("/api/v1/auth/login", [
            "email" => "tidakada@gmail.com",
            "password" => "salah"
        ])
        ->assertNotFound();
    }
}
