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
    function test_register_success() {
        $this->post("/api/v1/auth/register", [
            "username" => "farish",
            "email" => "farish@gmail.com",
            "password" => "farish"
        ])
        ->assertCreated();

        DB::table('users')->where("username", "farish")->delete();
    }

    function test_register_failed() {
        $this->post("/api/v1/auth/register", [
            "username" => "farish",
            "email" => "farish",
            "password" => ""
        ])
        ->assertBadRequest();
    }

    function test_register_account_exist() {
        $this->post("/api/v1/auth/register", [
            "username" => "admin",
            "email" => "admin@gmail.com",
            "password" => "admin"
        ])
        ->assertUnauthorized();
    }

    function test_login_success() {
        $this->post("/api/v1/auth/login", [
            "email" => "admin@gmail.com",
            "password" => "admin"
        ])
        ->assertOk();
    }

    function test_login_wrong_password() {
        $this->post("/api/v1/auth/login", [
            "email" => "admin@gmail.com",
            "password" => "salah"
        ])
        ->assertUnauthorized();
    }

    function test_login_account_not_exist() {
        $this->post("/api/v1/auth/login", [
            "email" => "tidakada@gmail.com",
            "password" => "salah"
        ])
        ->assertNotFound();
    }
}
