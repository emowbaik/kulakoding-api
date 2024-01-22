<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    public $token = "3|VknymWfmdilwLfjuHw81VsE3X19giJei3JCJPdAYd8f844fa";
    /**
     * A basic feature test example.
     */
    function testCreateProjectSuccess() : void {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])
        ->post("/api/v1/project", [
            "nama_project" => "website",
            "deskripsi" => "lorem"
        ])
        ->assertCreated();
    }

    function testCreateProjectFailed() : void {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])
        ->post("/api/v1/project", [
            "nama_project" => "",
            "deskripsi" => ""
        ])
        ->assertBadRequest();
    }

    function testCreateProjectUnauthorized() : void {
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
