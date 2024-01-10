<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    public $token = "4|j6lykgbq14WMPcbm978mHDZJxiqFtVbH5hjKOJ12cd3a0022";
    /**
     * A basic feature test example.
     */
    function test_create_project_success() : void {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])
        ->post("/api/v1/project", [
            "nama_project" => "website",
            "deskripsi" => "lorem"
        ])
        ->assertCreated();
    }

    function test_create_project_failed() : void {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])
        ->post("/api/v1/project", [
            "nama_project" => "",
            "deskripsi" => ""
        ])
        ->assertUnauthorized();
    }

    function test_access_project_unauthorized() : void {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . "baskdjaksda",
        ])
        ->post("/api/v1/project", [
            "nama_project" => "",
            "deskripsi" => ""
        ])
        ->assertInternalServerError();
    }
}
