<?php

it('returns all quotes as json')
    ->getJson('api/quotes')
    ->assertSuccessful()
    ->assertJsonStructure([
        'quotes',
        'total',
    ]);

it('returns a random quote as json')
    ->getJson('api/quotes/random')
    ->assertSuccessful()
    ->assertJsonStructure([
        'id',
        'author',
        'quote',
    ]);

it('returns a specific quote by id as json')
    ->getJson('api/quotes/1')
    ->assertSuccessful()
    ->assertJsonStructure([
        'id',
        'author',
        'quote',
    ])
    ->assertJson(['id' => 1]);
