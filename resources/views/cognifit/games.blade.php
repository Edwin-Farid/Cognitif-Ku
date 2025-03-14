@extends('layouts.app')

@section('content')
    <style>
        #cognifit-container {
            width: 100%;
            height: 600px;
            border: 1px solid #ddd;
            margin-top: 20px;
        }

        .game-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .game-list li {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
            background: #f9f9f9;
        }

        .game-icon {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .game-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .play-button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .play-button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="container">
        <div id="cognifit-container"></div>

        <h1>CogniFit Games</h1>

        <ul class="game-list">
            @foreach ($games as $gameKey => $game)
                <li>
                    {{-- Menampilkan ikon game --}}
                    <img class="game-icon" src="{{ $game->getAssets()['images']['icon'] ?? 'https://via.placeholder.com/80' }}" alt="{{ $game->getAssets()['titles']['en'] ?? 'Game Icon' }}">

                    {{-- Menampilkan judul game --}}
                    <div class="game-title">
                        {{ $game->getAssets()['titles']['en'] ?? 'No title available' }}
                    </div>

                    {{-- Tombol bermain game --}}
                    <button class="play-button" onclick="startGame('{{ $game->getKey() }}')">
                        Play Game
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
